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

        $this->buildObjects();
        
        return $this->getCache($resolveClass);
    }

    /**
     * Create a map of dependencies for a class
     *
     * @param int $level Level for dependency
     * @param string $class Class wich tree will build
     */
    private function buildDependencyTree(int $level, string $class)
    {
        //initial back slash
        $class = (strpos($class, '\\') > 0) ? '\\' . $class : $class;
        
        //initialize array
        $this->dependencyTree[$level][$class] = [];
        
        //create reflection class
        $reflectionClass = new \ReflectionClass($class);
        
        //get parameter from constructor
        $param = $reflectionClass->getConstructor()->getParameters();
        
        //loop parameter
        foreach ($param as $key => $value) {
            
            //if there is parameter with callable type
            if ($value->hasType() === true && class_exists((string)$value->getType())) {
                
                //store dependency
                $this->dependencyTree[$level][$class][] = '\\' . $value->getClass()->name;
                
                //call recursive
                $this->buildDependencyTree($level + 1, $value->getClass()->name);
            }
        }
    }
    
    /**
     * Build objects start from dependencyTree
     *
     */
    private function buildObjects()
    {
        //reverse array for build first required classes
        $array = array_reverse($this->dependencyTree);
        
        //deep dependency level
        foreach ($array as $key => $value) {
            
            //class
            foreach ($value as $class => $dependency) {
                
                //try to find object in class
                $object = $this->getCache($class);
                
                $objectReflection = new \ReflectionClass($class);
                
                //if object is not in cache and need arguments try to build
                if ($object === null && sizeof($dependency) > 0) {
                    
                    //build arguments
                    $args = $this->buildObjectDependency($dependency);
                    
                    //store object with dependencies in cache
                    $this->setCache($class, $objectReflection->newInstanceArgs($args));
                    
                    continue;
                }
                
                if ($object === null) {
                    
                    //store object in cache
                    $this->setCache($class, $objectReflection->newInstance());
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
            $args[] = $this->getCache($argValue);
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
    
    /**
     * Internal function for store objects in cache
     *
     * @param string $name Object name
     * @param object $object Object to store
     */
    private function setCache(string $name, $object)
    {
        $this->cache[$name] = $object;
    }
    
    /**
     * Internal function for retrive objects from cache
     *
     * @param string $name Object to search
     * @return object|null
     */
    private function getCache(string $name)
    {
        if (array_key_exists($name, $this->cache)) {
            return $this->cache[$name];
        }

        return null;
    }
}
