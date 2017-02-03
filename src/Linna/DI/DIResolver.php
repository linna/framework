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
     * @var array $rules For resolve scalar arguments and unexpected behavior
     */
    private $rules = [];
    
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
     * Set rules for unserolvable classes
     *
     * @param array $rules
     */
    public function rules(array $rules)
    {
        $this->rules = $rules;
    }
    
    /**
     * Resolve dependencies for given class
     *
     * @param string $resolveClass Class needed
     * @param array $rules Rules for resolving
     *
     * @return object|null Instance of resolved class
     */
    public function resolve(string $resolveClass, array $rules = [])
    {
        $this->rules = array_merge($this->rules, $rules);
        
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

            //get parameter from constructor
            $parameters = (new \ReflectionClass($class))->getConstructor()->getParameters();
            
            //loop parameter
            foreach ($parameters as $value) {
                
                //get argument properties
                $paramHasType = (bool) $value->hasType();
                $paramType = (string) $value->getType();
                $paramPosition = (int) $value->getPosition();
                $paramName = (string) $value->getName();
                $paramClass = (is_object($value->getClass())) ? '\\' . $value->getClass()->name : null;
                
                //make argument description
                $paramDescription = ['class' => $paramClass, 'type' => $paramType, 'name' => $paramName,  'position' => $paramPosition];
                
                //check if argument is already stored
                $notAlreadyStored = !in_array($paramDescription, $this->dependencyTree[$level][$class]);
                
                //check if param has primitive type
                $isPrimitiveType = in_array($paramType, ['bool', 'int', 'string', 'array', '']);
                
                //if there is parameter with callable type
                if ($paramHasType && class_exists($paramType) && $notAlreadyStored) {
                    
                    //push values in stack for simulate later recursive function
                    $stack->push([$level, $class]);

                    //store dependency
                    $this->dependencyTree[$level][$class][] = $paramDescription;

                    //update values for simulate recursive function
                    $level = $level + 1;
                    $class = $paramClass;

                    //return to main while
                    continue 2;
                }
                
                //if there is argument not typed or wit primitive type
                if ($isPrimitiveType && $notAlreadyStored) {
                    //store dependency
                    $this->dependencyTree[$level][$class][] = $paramDescription;
                }
            }
            
            //if stack is empty break while end exit from function
            if ($stack->count() === 0) {
                break;
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
        foreach ($array as $value) {

            //class
            foreach ($value as $class => $dependency) {
                
                //try to find object in class
                $object = $this->cache[$class] ?? null;

                //if object is not in cache and need arguments try to build
                if ($object === null && sizeof($dependency) > 0) {

                    //build arguments
                    $args = $this->buildObjectDependency($class, $dependency);
                    
                    //store object with dependencies in cache
                    $this->cache[$class] = (new \ReflectionClass($class))->newInstanceArgs($args);
                    
                    continue;
                }

                if ($object === null) {
                    
                    //store object in cache
                    $this->cache[$class] = (new \ReflectionClass($class))->newInstanceArgs();
                }
            }
        }
    }

    /**
     * Build dependency for a object
     *
     * @param string $class Class name
     * @param array $dependency Arguments required from object
     *
     * @return array
     */
    private function buildObjectDependency(string $class, array $dependency): array
    {
        //initialize arguments array
        $args = [];

        //argument required from class
        foreach ($dependency as $argValue) {

            //add to array of arguments
            $args[] = $this->cache[$argValue['class']] ?? null;
        }
        
        //check if there is rules for this class
        if (isset($this->rules[$class])) {
            //merge arguments
            $args = array_replace($args, $this->rules[$class]);
        }
        
        return $args;
    }

    /**
     * Store and object that DI connot resolve
     *
     * @param string $name Object name
     * @param object $object Object to store
     */
    public function cache(string $name, $object)
    {
        $this->cache[$name] = $object;
    }
}
