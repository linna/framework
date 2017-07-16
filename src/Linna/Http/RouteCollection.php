<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Http;

use Linna\TypedObjectArray;

/**
 * Route Collection
 */
class RouteCollection extends TypedObjectArray
{
    /**
     * Contructor.
     * 
     * @param array $array
     */
    public function __construct(array $array = [])
    {
        parent::__construct(Route::class, $array);
    }
    
    /**
     * Return collection as array.
     * 
     * @return array
     */
    public function toArray() : array
    {
        $array = $this->getArrayCopy();
        $tmp = [];
        
        foreach ($array as $route) {
            $tmp[] = $route->toArray();
        }
        
        return $tmp;
    }
}