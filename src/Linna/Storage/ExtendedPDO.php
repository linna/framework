<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
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
     * @var bool Status for last operation.
     */
    private bool $lastOperationStatus = false;

    /**
     * Executes an SQL statement with parameters,
     * returning a result set as a PDOStatement object
     *
     * @param string $query SQL statement
     * @param array  $param Parameter as array as PDOStatement::bindParam
     *
     * @return PDOStatement
     */
    public function queryWithParam(string $query, array $param): PDOStatement
    {
        $statement = $this->prepare($query);

        foreach ($param as $value) {
            $this->checkValue($value);

            //reassign as reference
            //because bindParam need it as reference
            $ref = $value;
            $ref[1] = &$value[1];

            \call_user_func_array([$statement, "bindParam"], $ref);
        }

        $this->lastOperationStatus = $statement->execute();

        return $statement;
    }

    /**
     * Return the last opration status.
     *
     * @return bool
     */
    public function getLastOperationStatus(): bool
    {
        return $this->lastOperationStatus;
    }

    /**
     * Check values passed to queryWithParam.
     *
     * @param array $value
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    private function checkValue(array &$value): void
    {
        if (\count($value) < 2) {
            throw new InvalidArgumentException('Parameters array must contain at least two elements with this form: [\':name\', \'value\'].');
        }

        if (\strpos($value[0], ':') !== 0) {
            throw new InvalidArgumentException('Parameter name will be in the form :name.');
        }
    }
}
