<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\DI;

class DIResolver
{
    private $cache = array();
    
    private $dependencyTree = array();

    public function __construct()
    {
    }

    public function resolve($resolveClass)
    {
        $this->buildDependencyTree(0, $resolveClass);

        $this->buildObjects();
        
        return $this->getCache($resolveClass);
    }

    private function buildDependencyTree($level, $class)
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
            if ($value->hasType() === true && class_exists($value->getType())) {
                
                //store dependency
                $this->dependencyTree[$level][$class][] = '\\' . $value->getClass()->name;
                
                //call recursive
                $this->buildDependencyTree($level + 1, $value->getClass()->name);
            }
        }
    }

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
    
    private function buildObjectDependency($dependency)
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
    
    public function cacheUnResolvable($name, $object)
    {
        $this->cache[$name] = $object;
    }

    private function setCache($name, $object)
    {
        $this->cache[$name] = $object;
    }

    private function getCache($name)
    {
        if (array_key_exists($name, $this->cache)) {
            return $this->cache[$name];
        }

        return null;
    }
}
