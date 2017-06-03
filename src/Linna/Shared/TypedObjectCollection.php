<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Shared;

use TypeError;

/**
 * Typed Object Collection.
 * Use it to create a collection in which the elements, 
 * are all of the same type.
 */
class TypedObjectCollection 
{
    /**
     * @var array Array of object.
     */
    private $data = [];

    /**
     *
     * @var string Type of object of collection.
     */
    private $type;

    /**
     * Constructor.
     * 
     * @param string $type
     * @throws InvalidArgumentException
     */
    public function __construct(string $type)
    {
        if (!class_exists($type)){
           throw new TypeError('Argument must be valid class name'); 
        }

        $this->type = $type;
    }

    /**
     * Push an element inside collection.
     * 
     * @param mixed $elements
     * @return bool
     * @throws InvalidArgumentException
     */
    public function push(...$elements) : bool
    {
        foreach ($elements as $element)
        {
            if ($element instanceof $this->type){
                $this->data[] = $element;
                continue;
            }

            throw new TypeError('Argument must be instance of '.$this->type);
        }

        return true;
    }

    /**
     * Return the type of collection's objects
     * 
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Return an array containg all collection's object.
     * 
     * @return array
     */
    public function toArray() : array
    {
        return $this->data;
    }

    /**
     * Return the collection's size.
     * 
     * @return int
     */
    public function count() : int
    {
        return count($this->data);
    }
}