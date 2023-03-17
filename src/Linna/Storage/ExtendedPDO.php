<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Storage;

use PDO;
use PDOStatement;
use InvalidArgumentException;

/**
 * Extended PDO.
 */
class ExtendedPDO extends PDO
{
    /** @var bool Status for last operation. */
    private bool $lastOperationStatus = false;

    /**
     * Executes an SQL statement with parameters, returning a result set as a
     * PDOStatement object.
     *
     * @param string       $query  SQL statement.
     * @param array<mixed> $params Parameter as array as <code>PDOStatement::bindParam</code>.
     *
     * @return PDOStatement|false False in case of failure.
     */
    public function queryWithParam(string $query, array $params): PDOStatement|false
    {
        $statement = $this->prepare($query);

        $callback = [$statement, "bindParam"];

        if (\is_callable($callback)) {
            foreach ($params as $value) {
                $this->checkValue($value);

                //reassign as reference
                //because bindParam need it as reference
                $ref = $value;
                $ref[1] = &$value[1];

                \call_user_func_array($callback, $ref);
            }

            $this->lastOperationStatus = $statement->execute();
        }

        return $statement;
    }

    /**
     * Return the last opration status.
     *
     * @return bool True if the last operation succeeded, false otherwise.
     */
    public function getLastOperationStatus(): bool
    {
        return $this->lastOperationStatus;
    }

    /**
     * Check values passed to the method queryWithParam.
     *
     * @param array<mixed> $value The value which will be checked.
     *
     * @return void
     *
     * @throws InvalidArgumentException If the values are passed in the wrong form.
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
