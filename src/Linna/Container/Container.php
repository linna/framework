<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Container;

use ArrayAccess;
use Linna\Container\Exception\NotFoundException;
use Linna\Shared\ArrayAccessTrait;
use Linna\Shared\PropertyAccessTrait;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionNamedType;

/**
 * Dependency Injection Container and Resolver.
 */
class Container implements ContainerInterface, ArrayAccess
{
    use PropertyAccessTrait;
    use ArrayAccessTrait;

    /**
     * @const string
     */
    private const NO_TYPE = 'NO_TYPE';

    /**
     * @const string
     */
    public const RULE_INTERFACE = 'interfaces';

    /**
     * @const string
     */
    public const RULE_ARGUMENT = 'arguments';

    /** @var array<mixed> Contains object already resolved. */
    private array $cache = [];

    /** @var array<mixed> Hierarchical structure of dependencies. */
    protected array $tree = [];

    /**
     * @var array<mixed> Rules for resolve scalar arguments or unexpected behaviors.
     */
    //protected array $rules = [];

    /**
     * Class Constructor.
     *
     * @param array<mixed> $rules Rules for resolve scalar arguments or unexpected behaviors.
     */
    public function __construct(protected array $rules = [])
    {
        //$this->rules = $rules;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundException No entry was found for **this** identifier.
     *
     * @return mixed Entry.
     */
    public function get(string $id)
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
    public function has(string $id): bool
    {
        return isset($this->cache[$id]);
    }

    /**
     * Store a value inside container.
     *
     * @param string $id
     * @param mixed  $value
     *
     * @return void
     */
    public function set(string $id, mixed $value): void
    {
        $this->cache[$id] = $value;
    }

    /**
     * Delete value from container.
     *
     * @param string $id
     *
     * @return bool
     */
    public function delete(string $id): void
    {
        if (\array_key_exists($id, $this->cache)) {
            //delete value
            unset($this->cache[$id]);

            //return function result
            //return true;
        }

        //return false;
    }

    /**
     * Resolve dependencies for given class.
     *
     * @param string       $class An existing class.
     * @param array<mixed> $rules Custom rules.
     *
     * @return object|null Instance of resolved class
     */
    public function resolve(string $class, array $rules = []): ?object
    {
        //reset tree;
        $this->tree = [];

        //merge rules passed as parameter with general rules
        $this->rules = \array_merge($this->rules, $rules);

        //build dependency tree
        $this->buildTreeRecursive($class);
        //$this->buildTree($class);

        //build objects
        $this->buildObjects();

        //return required class
        return $this->cache[$class] ?? null;
    }

    /**
     * Create a map of dependencies for a class
     *
     * @param string $class Class wich tree will build
     * @param int    $level Level for dependency
     *
     * @return void
     */
    private function buildTreeRecursive(string $class, int $level = 0): void
    {
        //initialize array
        $this->tree[$level][$class] = [];

        //get parameter from constructor
        //casting needed to avoid foreach loop on null
        $parameters = (array) (new ReflectionClass($class))->getConstructor()?->getParameters();

        //loop parameter
        foreach ($parameters as $param) {
            //Function ReflectionType::__toString() is deprecated
            //FROM https://www.php.net/manual/en/migration74.deprecated.php
            //Calls to ReflectionType::__toString() now generate a deprecation notice.
            //This method has been deprecated in favor of ReflectionNamedType::getName()
            //in the documentation since PHP 7.1, but did not throw a deprecation notice
            //for technical reasons.
            //Get the data type of the parameter
            $type = ($param->getType() instanceof ReflectionNamedType) ?
                $param->getType()->getName() :
                self::NO_TYPE;

            //if parameter is an interface
            //check rules for an implementation and
            //replace interface with implementation
            if (\interface_exists($type)) {
                //get the position of the current parameter for resolve the rule
                $position = $param->getPosition();
                //override type with interface implamentation
                //declared in rules
                $type = $this->rules[self::RULE_INTERFACE][$class][$position];
            }

            //if there is a parameter with callable type
            if (\class_exists($type)) {
                //store dependency
                $this->tree[$level][$class][] = $param;

                //call recursive, head recursion
                $this->buildTreeRecursive($type, $level + 1);

                //continue
                continue;
            }

            $this->tree[$level][$class][] = $param;
        }
    }

    /**
     * Create a dependencies map for a class.
     * !!!This method will be removed!!!
     *
     * @param string $class
     *
     * @return void
     *
     * @deprecated since version 0.27.0 Return to recursive build of the tree
     *                                  because is 1.5x time fast.
     */
    /*private function buildTree(string $class): void
    {
        $level = 0;
        $stack = new SplStack();

        while (true) {

            //initialize array if not already initialized
            $this->tree[$level][$class] ??= [];

            //get parameter from constructor
            //can return error when constructor not declared
            $constructor = (new ReflectionClass($class))->getConstructor();//->getParameters();
            //this should resolve the error when a class without constructor is encountered
            $parameters = \is_null($constructor) ? [] : $constructor->getParameters();

            //loop parameter
            foreach ($parameters as $param) {

                //check if argument is already stored
                $notAlreadyStored = !\in_array($param, $this->tree[$level][$class]);

                //Function ReflectionType::__toString() is deprecated
                //FROM https://www.php.net/manual/en/migration74.deprecated.php
                //Calls to ReflectionType::__toString() now generate a deprecation notice.
                //This method has been deprecated in favor of ReflectionNamedType::getName()
                //in the documentation since PHP 7.1, but did not throw a deprecation notice
                //for technical reasons.
                $type = ($param->getType() !== null) ? $param->getType()->getName() : self::NO_TYPE;

                //if there is parameter with callable type
                if (\class_exists($type) && $notAlreadyStored) {

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
    }*/

    /**
     * Build objects from dependencyTree.
     *
     * @return void
     */
    private function buildObjects(): void
    {
        //deep dependency level, start to array end for not use array_reverse
        for ($i = \count($this->tree) - 1; $i >= 0; $i--) {
            //class
            foreach ($this->tree[$i] as $class => $arguments) {
                //try to find object in class
                if (isset($this->cache[$class])) {
                    // no build need
                    continue;
                }

                //object is not in cache and need arguments try to build it
                if (\count($arguments)) {
                    //build arguments
                    $args = $this->buildArguments($class, $arguments);

                    //store object with dependencies in cache
                    $this->cache[$class] = (new ReflectionClass($class))->newInstanceArgs($args);

                    continue;
                }

                //store object in cache
                $this->cache[$class] = (new ReflectionClass($class))->newInstance();
            }
        }
    }

    /**
     * Build dependency for a object.
     *
     * @param string       $class
     * @param array<mixed> $dependency
     *
     * @return array<mixed>
     */
    private function buildArguments(string $class, array $dependency): array
    {
        //initialize arguments array
        $args = [];

        //argument required from class
        foreach ($dependency as $argValue) {
            $argType = ($argValue->getType() instanceof ReflectionNamedType) ?
                $argValue->getType()->getName() :
                self::NO_TYPE;

            if (\interface_exists($argType)) {
                //get the position of the current parameter for resolve the rule
                $position = $argValue->getPosition();
                //retrive concrete class bound to inteface in rules
                $args[] = $this->cache[$this->rules[self::RULE_INTERFACE][$class][$position]];
                continue;
            }

            if (\class_exists($argType)) {
                //add to array of arguments
                $args[] = $this->cache[$argType];
                continue;
            }

            $args[] = null;
        }

        //check if there is rules for this class
        if (isset($this->rules['arguments'][$class])) {
            //merge arguments
            $args = \array_replace($args, $this->rules[self::RULE_ARGUMENT][$class]);
        }

        return $args;
    }
}
