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
     * @param string $class
     * @param array  $rules
     *
     * @return object|null Instance of resolved class
     */
    public function resolve(string $class, array $rules = [])
    {
        //merge rules passed as parameter with general rules
        $this->rules = array_merge($this->rules, $rules);

        //check if before class name there is a \
        $class = (strpos($class, '\\') !== 0) ? '\\'.$class : $class;

        //build dependency tree
        $this->buildTree($class);

        //build objects
        $this->buildObjects();

        //return required class
        return $this->cache[$class] ?? null;
    }

    /**
     * Create a dependencies map for a class.
     *
     * @param string $class
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
            foreach ($parameters as $param) {

                //check if argument is already stored
                $notAlreadyStored = !in_array($param, $this->dependencyTree[$level][$class]);

                //if there is parameter with callable type
                if (class_exists((string) $param->getType()) && $notAlreadyStored) {

                    //push values in stack for simulate later recursive function
                    $stack->push([$level, $class]);

                    //store dependency
                    $this->dependencyTree[$level][$class][] = $param;

                    //update values for simulate recursive function
                    $level = $level + 1;
                    $class = (is_object($param->getClass())) ? '\\'.$param->getClass()->name : null;

                    //return to main while
                    continue 2;
                }

                if ($notAlreadyStored) {
                    //store dependency
                    $this->dependencyTree[$level][$class][] = $param;
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
            foreach ($this->dependencyTree[$i] as $class => $arguments) {

                //try to find object in class
                $object = $this->cache[$class] ?? null;

                //if object is not in cache and need arguments try to build
                if ($object === null && count($arguments) > 0) {

                    //build arguments
                    $args = $this->buildArguments($class, $arguments);

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
     * @param string $class
     * @param array  $dependency
     *
     * @return array
     */
    private function buildArguments(string $class, array $dependency): array
    {
        //initialize arguments array
        $args = [];

        //argument required from class
        foreach ($dependency as $argValue) {
            $paramClass = (is_object($argValue->getClass())) ? '\\'.$argValue->getClass()->name : null;
            //add to array of arguments
            $args[] = $this->cache[$paramClass] ?? null;
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
     * @param string $name
     * @param object $object
     */
    public function cache(string $name, $object)
    {
        $this->cache[$name] = $object;
    }
}
