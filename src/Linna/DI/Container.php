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

use Linna\DI\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Dependency Injection Container and Resolver.
 */
class Container implements ContainerInterface, \ArrayAccess
{
    use PropertyAccessTrait;
    use ArrayAccessTrait;

    /**
     * @var array Contains object already resolved
     */
    private $cache = [];

    /**
     * @var array A map for resolve dependencies
     */
    protected $tree = [];

    /**
     * @var array For resolve scalar arguments and unexpected behavior
     */
    protected $rules = [];

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        throw new NotFoundException('No entry was found for this identifier');
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return isset($this->cache[$key]);
    }

    /**
     * Store a value inside container.
     *
     * @param string   $key
     * @param callable $value
     */
    public function set(string $key, $value)
    {
        $this->cache[$key] = $value;
    }

    /**
     * Delete value from container.
     *
     * @param string $key
     */
    public function delete(string $key) : bool
    {
        if (array_key_exists($key, $this->cache)) {

            //delete value
            unset($this->cache[$key]);

            //return function result
            return true;
        }

        return false;
    }

    /**
     * Set rules for unserolvable classes.
     *
     * @param array $rules
     */
    public function setRules(array $rules)
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
            if (!isset($this->tree[$level][$class])) {
                $this->tree[$level][$class] = [];
            }

            //get parameter from constructor
            $parameters = (new \ReflectionClass($class))->getConstructor()->getParameters();

            //loop parameter
            foreach ($parameters as $param) {

                //check if argument is already stored
                $notAlreadyStored = !in_array($param, $this->tree[$level][$class]);

                //if there is parameter with callable type
                if (class_exists((string) $param->getType()) && $notAlreadyStored) {

                    //push values in stack for simulate later recursive function
                    $stack->push([$level, $class]);

                    //store dependency
                    $this->tree[$level][$class][] = $param;

                    //update values for simulate recursive function
                    $level++;
                    $class = (is_object($param->getClass())) ? $param->getClass()->name : null;

                    //return to main while
                    continue 2;
                }

                if ($notAlreadyStored) {
                    //store dependency
                    $this->tree[$level][$class][] = $param;
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
        for ($i = count($this->tree) - 1; $i >= 0; $i--) {

            //class
            foreach ($this->tree[$i] as $class => $arguments) {

                //try to find object in class
                $object = $this->cache[$class] ?? null;

                //if object is not in cache and need arguments try to build
                if ($object === null && count($arguments) !== 0) {

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
            $paramClass = (is_object($argValue->getClass())) ? $argValue->getClass()->name : null;
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
}
