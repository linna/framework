<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

declare(strict_types=1);

namespace Linna\DI;

/**
 * Dependency Injection Resolver
 *
 */
class DIResolver
{
    /**
     * @var array $cache Contains object already resolved
     */
    private $cache;
    
    /**
     * @var array $dependencyTree A map for resolve dependencies
     */
    private $dependencyTree;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->cache = array();
        $this->dependencyTree = array();
    }

    /**
     * Resolve dependencies for given class
     *
     * @param string $resolveClass Class needed
     *
     * @return object|null Instance of resolved class
     */
    public function resolve(string $resolveClass)
    {
        $resolveClass = (strpos($resolveClass, '\\') !== 0) ? '\\' . $resolveClass : $resolveClass;
        
        $this->buildDependencyTree($resolveClass);
        
        $this->buildObjects(array_reverse($this->dependencyTree));
        
        return $this->cache[$resolveClass] ?? null;
    }

    /**
     * Create a dependencies map for a class
     *
     * @param string $class Class wich tree will build
     */
    private function buildDependencyTree(string $class)
    {
        //set start level
        $level = 0;
        
        //create stack
        $stack = new \SplStack;
        
        //iterate
        while (true) {
            
            //initialize array if not already initialized
            if (!isset($this->dependencyTree[$level][$class])) {
                $this->dependencyTree[$level][$class] = [];
            }

            //create reflection class
            $reflectionClass = new \ReflectionClass($class);

            //get parameter from constructor
            $param = $reflectionClass->getConstructor()->getParameters();

            //loop parameter
            foreach ($param as $key => $value) {

                //if there is parameter with callable type
                if ($value->hasType() === true && class_exists((string) $value->getType())) {
                    
                    //store parameter class name
                    $tmpClass = '\\' . $value->getClass()->name;
                    
                    //if class are not already resolved
                    if (!in_array($tmpClass, $this->dependencyTree[$level][$class])) {
                        
                        //push values in stack for simulate later recursive function
                        $stack->push([$level, $class]);

                        //store dependency
                        $this->dependencyTree[$level][$class][] = $tmpClass;

                        //update values for simulate recursive function
                        $level = $level + 1;
                        $class = $tmpClass;
                        
                        //return to main while
                        continue 2;
                    }
                }
            }
            
            //if stack is empty break while end exit from function
            if ($stack->count() === 0) {
                return;
            }
            
            //get last value pushed into stack;
            list($level, $class) = $stack->pop();
        }
    }

    /**
     * Build objects from dependencyTree
     *
     * @param array $array Dependency Tree
     */
    private function buildObjects(array $array)
    {
        //deep dependency level
        foreach ($array as $key => $value) {

            //class
            foreach ($value as $class => $dependency) {

                //try to find object in class
                $object = $this->cache[$class] ?? null;

                //if object is not in cache and need arguments try to build
                if ($object === null && sizeof($dependency) > 0) {

                    //build arguments
                    $args = $this->buildObjectDependency($dependency);
                    
                    //create reflection class
                    $objectReflection = new \ReflectionClass($class);
                    
                    //store object with dependencies in cache
                    $this->cache[$class] = $objectReflection->newInstanceArgs($args);
                    
                    continue;
                }

                if ($object === null) {
                    
                    //create reflection class
                    $objectReflection = new \ReflectionClass($class);
                    
                    //store object in cache
                    $this->cache[$class] = $objectReflection->newInstanceArgs();
                }
            }
        }
    }

    /**
     * Build dependency for a object
     *
     * @param array $dependency Arguments required from object
     * @return array
     */
    private function buildObjectDependency(array $dependency): array
    {
        //initialize arguments array
        $args = [];

        //argument required from class
        foreach ($dependency as $argKey => $argValue) {

            //add to array of arguments
            $args[] = $this->cache[$argValue] ?? null;
        }

        return $args;
    }

    /**
     * Store and object that DI connot resolve
     *
     * @param string $name Object name
     * @param object $object Object to store
     */
    public function cacheUnResolvable(string $name, $object)
    {
        $this->cache[$name] = $object;
    }
}
