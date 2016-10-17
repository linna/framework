<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
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
     * @var array $dependencyTree A map for relove dependencies
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
     * @return object Instance of resolved class
     */
    public function resolve(string $resolveClass)
    {
        $this->buildDependencyTree(0, $resolveClass);
        
        $this->buildObjects(array_reverse($this->dependencyTree));
        
        return $this->cache[$resolveClass] ?? null;
    }

    /**
     * Create a map of dependencies for a class
     *
     * @param int $level Level for dependency
     * @param string $class Class wich tree will build
     */
    private function buildDependencyTree(int $level, string $class)
    {
        //create stack
        $stack = new \SplStack;
        
        while (true) {
            
            //initial back slash
            $class = (strpos($class, '\\') !== 0) ? '\\' . $class : $class;

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
                    
                    //if class are not already resolved
                    if (!in_array('\\' . $value->getClass()->name, $this->dependencyTree[$level][$class])) {
                        
                        //push values in stack for simulate later recursive function
                        $stack->push([$level, $class]);

                        //store dependency
                        $this->dependencyTree[$level][$class][] = '\\' . $value->getClass()->name;

                        //update values for simulate recursive function
                        $level = $level + 1;
                        $class = $value->getClass()->name;
                        
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
     * Build objects start from dependencyTree
     *
     */
    private function buildObjects($array)
    {
        //deep dependency level
        foreach ($array as $key => $value) {

            //class
            foreach ($value as $class => $dependency) {

                //try to find object in class
                $object = $this->cache[$class] ?? null;

                $objectReflection = new \ReflectionClass($class);

                //if object is not in cache and need arguments try to build
                if ($object === null && sizeof($dependency) > 0) {

                    //build arguments
                    $args = $this->buildObjectDependency($dependency);

                    //store object with dependencies in cache
                    $this->cache[$class] = $objectReflection->newInstanceArgs($args);
                    
                    continue;
                }

                if ($object === null) {

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
