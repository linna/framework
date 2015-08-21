<?php

/**
 * Leviu
 *
 * This work would be a little PHP framework, a learn exercice. 
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 * @version 0.1.0
 */

namespace Leviu\core;

/**
 * Model 
 * - Parent class for model casses
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 */
class Model
{
    /**
     * @var object Database Connection
     */
    //protected $db = null;

    /**
     * Model constructor
     * 
     * Only connect to database
     * @since 0.1.0
     */
    public function __construct()
    {
        //$this->db = Database::connect();
    }
}
