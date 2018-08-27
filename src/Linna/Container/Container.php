<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Container;

use ArrayAccess;
use Linna\Container\Exception\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use SplStack;

/**
 * Dependency Injection Container and Resolver.
 */
class Container implements ContainerInterface, ArrayAccess
{
    use PropertyAccessTrait;
    use ArrayAccessTrait;

    /**
     * @var array Contains object already resolved.
     */
    private $cache = [];

    /**
     * @var array Hierarchical structure of dependencies.
     */
    protected $tree = [];

    /**
     * @var array Rules for resolve scalar arguments or unexpected behaviors.
     */
    protected $rules = [];

    /**
     * Class Constructor.
     *
     * @param array $rules Rules for resolve scalar arguments or unexpected behaviors.
     */
    public function __construct(array $rules = [])
    {
        $this->rules = $rules;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if (isset($this->cache[$id])) {
            return $this->cache[$id];
        }

        throw new NotFoundException('No entry was found for this identifier.');
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($this->cache[$id]);
    }

    /**
     * Store a value inside container.
     *
     * @param string $id
     * @param mixed  $value
     */
    public function set(string $id, $value): void
    {
        $this->cache[$id] = $value;
    }

    /**
     * Delete value from container.
     *
     * @param string $id
     */
    public function delete(string $id): bool
    {
        if (array_key_exists($id, $this->cache)) {

            //delete value
            unset($this->cache[$id]);

            //return function result
            return true;
        }

        return false;
    }

    /**
     * Resolve dependencies for given class.
     *
     * @param string $class An existing class.
     * @param array  $rules Custom rules.
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
    private function buildTree(string $class): void
    {
        $level = 0;
        $stack = new SplStack();

        while (true) {

            //initialize array if not already initialized
            if (empty($this->tree[$level][$class])) {
                $this->tree[$level][$class] = [];
            }

            //get parameter from constructor
            $parameters = (new ReflectionClass($class))->getConstructor()->getParameters();

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
                    $class = $param->getClass()->name;

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
    private function buildObjects(): void
    {
        //deep dependency level, start to array end for not use array_reverse
        for ($i = count($this->tree) - 1; $i >= 0; $i--) {

            //class
            foreach ($this->tree[$i] as $class => $arguments) {

                //try to find object in class
                $object = $this->cache[$class] ?? null;

                //if object is not in cache and need arguments try to build
                if ($object === null && count($arguments)) {

                    //build arguments
                    $args = $this->buildArguments($class, $arguments);

                    //store object with dependencies in cache
                    $this->cache[$class] = (new ReflectionClass($class))->newInstanceArgs($args);

                    continue;
                }

                if ($object === null) {

                    //store object in cache
                    $this->cache[$class] = (new ReflectionClass($class))->newInstance();
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
            $paramClass = null;

            if (class_exists((string) $argValue->getType())) {
                $paramClass = $argValue->getClass()->name;
            }

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
