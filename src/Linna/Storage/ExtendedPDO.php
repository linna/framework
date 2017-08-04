<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Storage;

use PDO;
use PDOStatement;
use InvalidArgumentException;

/**
 * Extended PDO
 */
class ExtendedPDO extends PDO
{
    /**
     * Executes an SQL statement with parameters,
     * returning a result set as a PDOStatement object
     *
     * @param string $query SQL statement
     * @param array $param Parameter as array as PDOStatement::bindParam
     *
     * @return PDOStatement
     */
    public function queryWithParam(string $query, array $param) : PDOStatement
    {
        $statment = $this->prepare($query);

        foreach ($param as $value) {
            $this->checkValue($value);

            //reassign as reference
            //because bindParam need it as reference
            $ref = $value;
            $ref[1] = &$value[1];

            call_user_func_array([$statment, "bindParam"], $ref);
        }

        $statment->execute();

        return $statment;
    }

    /**
     * Check values passed to queryWithParam.
     *
     * @param array $value
     * @throws InvalidArgumentException
     */
    private function checkValue(array &$value)
    {
        if (count($value) < 2) {
            throw new InvalidArgumentException(__METHOD__.': Parameters array must contain at least two elements with this form: [\':name\', \'value\']');
        }

        if (strpos($value[0], ':') !== 0) {
            throw new InvalidArgumentException(__METHOD__.': Parameter name will be in the form :name');
        }
    }
}
