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
        $class = (strpos($class, '\\') > 0) ? '\\' . $class : $class;

        $this->dependencyTree[$level][$class] = [];

        $reflectionClass = new \ReflectionClass($class);
        
        $param = $reflectionClass->getConstructor()->getParameters();

        foreach ($param as $key => $value) {
            if ($value->hasType() === true && class_exists($value->getType())) {
                
                $this->dependencyTree[$level][$class][] = '\\' . $value->getClass()->name;

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
                
                //reflection class
                $objectReflection = new \ReflectionClass($class);
                
                //if object is not in cache and need arguments try to build
                if ($object === null && sizeof($dependency) > 0) {
                    //build arguments
                    $args = $this->buildObjectDependency($dependency);
                    //set object with instance class with arguments
                    $object = $objectReflection->newInstanceArgs($args);
                    //store it from cache
                    $this->setCache($class, $object);
                }
                
                if ($object === null) {
                    //set object with instance class without arguments
                    $object = $objectReflection->newInstance();
                    //store it from cache
                    $this->setCache($class, $object);
                }
            }
        }
    }
    
    private function buildObjectDependency($dependency)
    {
        $args = [];
        //argument required from class
        foreach ($dependency as $argKey => $argValue) {
            
            //find argument in cache
            $args[] = $this->getCache($argValue);
            /*
            //try to find argument in cache
            $cachedArg = $this->getCache($argValue);

            
            //if not in cache
            if ($cachedArg === null) {
                //create instance of arguments
                $temp = new $argValue();
                //store in cache
                $this->setCache($argValue,  $temp);
                //add to array of arguments
                $args[] = $temp;
            } else {
                //add to array of arguments cached parameter class
                $args[] = $cachedArg;
            }*/
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
