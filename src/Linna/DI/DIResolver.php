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
    private $class;
    private $cache = array();
    private $dependencyTree = array();

    public function __construct()
    {
    }

    public function resolve($class)
    {
        $this->class = $class;

        $this->buildDependencyTree(0, $class);

        $this->buidObjects();
        
        return $this->getCache($this->class);
    }

    public function addUnResolvable($name, $object)
    {
        $this->cache[$name] = $object;
    }

    private function buildDependencyTree($level, $class)
    {
        $class = (strpos($class, '\\') > 0) ? '\\' . $class : $class;

        $this->dependencyTree[$level][$class] = [];

        $reflectionClass = new \ReflectionClass($class);
        $constructor = $reflectionClass->getConstructor();
        $param = $constructor->getParameters();

        foreach ($param as $key => $value) {
            if ($value->hasType() === true && class_exists($value->getType())) {
                $this->dependencyTree[$level][$class][] = '\\' . $value->getClass()->name;

                $this->buildDependencyTree($level + 1, $value->getClass()->name);
            }
        }
    }

    private function buidObjects()
    {
        $array = array_reverse($this->dependencyTree);

        //deep dependency level
        foreach ($array as $key => $value) {
            
            //class
            foreach ($value as $class => $dependency) {
                
                $args = [];
                $object = $this->getCache($class);

                if ($object === null && sizeof($dependency) > 0) {
                    
                    //argument required from class
                    foreach ($dependency as $argKey => $argValue) {
                        if ($this->getCache($argValue) === null) {
                            $temp = new $argValue();
                            $this->setCache($argValue, $temp);
                            $args[] = $temp;
                        } else {
                            $args[] = $this->getCache($argValue);
                        }
                    }

                    $objectReflection = new \ReflectionClass($class);
                    $object = $objectReflection->newInstanceArgs($args);
                    $this->setCache($class, $object);
                }
                
                if ($object === null) {
                    $objectReflection = new \ReflectionClass($class);
                    $object = $objectReflection->newInstance();
                    $this->setCache($class, $object);
                }
            }
        }
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
