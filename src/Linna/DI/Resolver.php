<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\DI;

/**
 * Dependency Injection Resolver.
 */
class Resolver
{
    /**
     * @var array Contains object already resolved
     */
    private $cache;

    /**
     * @var array A map for resolve dependencies
     */
    private $dependencyTree;

    /**
     * @var array For resolve scalar arguments and unexpected behavior
     */
    private $rules = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->cache = [];
        $this->dependencyTree = [];
    }

    /**
     * Set rules for unserolvable classes.
     *
     * @param array $rules
     */
    public function rules(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Resolve dependencies for given class.
     *
     * @param string $class Class needed
     * @param array  $rules Rules for resolving
     *
     * @return object|null Instance of resolved class
     */
    public function resolve(string $class, array $rules = [])
    {
        $this->rules = array_merge($this->rules, $rules);

        $class = (strpos($class, '\\') !== 0) ? '\\'.$class : $class;

        $this->buildTree($class);

        $this->buildObjects();

        return $this->cache[$class] ?? null;
    }

    /**
     * Create a dependencies map for a class.
     *
     * @param string $class Class wich tree will build
     */
    private function buildTree(string $class)
    {
        //set start level
        $level = 0;

        //create stack
        $stack = new \SplStack();

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
                $paramType = (string) $value->getType();
                $paramPosition = (int) $value->getPosition();
                $paramName = (string) $value->getName();
                $paramClass = (is_object($value->getClass())) ? '\\'.$value->getClass()->name : null;

                //make argument description
                $paramDescription = ['class' => $paramClass, 'type' => $paramType, 'name' => $paramName,  'position' => $paramPosition];

                //check if argument is already stored
                $notAlreadyStored = !in_array($paramDescription, $this->dependencyTree[$level][$class]);

                //check if param has primitive type
                $isPrimitiveType = in_array($paramType, ['bool', 'int', 'string', 'array', '']);

                //if there is parameter with callable type
                if (class_exists($paramType) && $notAlreadyStored) {

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
     * Build objects from dependencyTree.
     */
    private function buildObjects()
    {
        //deep dependency level, start to array end for not use array_reverse
        for ($i = count($this->dependencyTree) - 1; $i >= 0; $i--) {

            //class
            foreach ($this->dependencyTree[$i] as $class => $dependency) {

                //try to find object in class
                $object = $this->cache[$class] ?? null;

                //if object is not in cache and need arguments try to build
                if ($object === null && count($dependency) > 0) {

                    //build arguments
                    $args = $this->buildArguments($class, $dependency);

                    //store object with dependencies in cache
                    $this->cache[$class] = (new \ReflectionClass($class))->newInstanceArgs($args);

                    continue;
                }

                if ($object === null) {

                    //store object in cache
                    $this->cache[$class] = (new \ReflectionClass($class))->newInstance();
                }
            }
        }
    }

    /**
     * Build dependency for a object.
     *
     * @param string $class      Class name
     * @param array  $dependency Arguments required from object
     *
     * @return array
     */
    private function buildArguments(string $class, array $dependency): array
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
     * Store and object that DI connot resolve.
     *
     * @param string $name   Object name
     * @param object $object Object to store
     */
    public function cache(string $name, $object)
    {
        $this->cache[$name] = $object;
    }
}
