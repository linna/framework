<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\DataMapper;

use InvalidArgumentException;
use RuntimeException;

/**
 * Universally Unique Identifier Version 4 utility.
 * https://en.wikipedia.org/wiki/Universally_unique_identifier
 * https://tools.ietf.org/html/rfc4122
 *
 */
class Uuid4
{
    /** @var string uuid in hexadecimal form. */
    private string $hexUUID = '';

    /** @var string uuid in binary form. */
    private string $binUUID = '';

    /**
     * Class Constructor.
     *
     * If no argument is passed, the class constructor generates a random uuid version 4.
     */
    public function __construct(string $uuid = '')
    {
        if (empty($uuid)) {
            $this->generate();
            $this->makeBin();

            return;
        }

        $this->check($uuid);
        $this->makeBin();
    }

    /**
     * Check the validity of an uuid.
     *
     * @param string $uuid The uuid which will be checked.
     *
     * @return void
     *
     * @throws InvalidArgumentException If the uuid not pass the check.
     */
    private function check(string $uuid): void
    {
        $uuid32 = \str_replace('-', '', $uuid);

        if (\preg_match('/^[0-9a-f]{8}[0-9a-f]{4}[4][0-9a-f]{3}[89ab][0-9a-f]{3}[0-9a-f]{12}$/i', $uuid32) !== 1) {
            throw new InvalidArgumentException('Invalid UUID version 4 provided.');
        }

        $this->hexUUID = $uuid;
    }

    /**
     * Get the uuid in hexadecimal format (36 chars separated by '-').
     *
     * @return string The uuid.
     */
    public function getHex(): string
    {
        return $this->hexUUID;
    }

    /**
     * Get the uuid in binary format (16 byte raw).
     *
     * @return string The uuid.
     */
    public function getBin(): string
    {
        return $this->binUUID;
    }

    /**
     * Return the uuid in 16 byte binary format.
     *
     * @return void
     *
     * @throws RuntimeException Something (internally to PHP) went wrong generating binary UUID value.
     */
    private function makeBin(): void
    {
        if (($uuid = \hex2bin(\str_replace('-', '', $this->hexUUID))) === false) {
            throw new RuntimeException('Something went wrong generating binary UUID value.');
        }

        $this->binUUID = $uuid;
    }

    /**
     * Generate a random uuid version 4 in hex format.
     *
     * @codeCoverageIgnore
     *
     * @return void
     */
    private function generate(): void
    {
        $this->hexUUID = \sprintf(
            '%s-%s-%s-%s-%s',
            // 8 hex characters
            \bin2hex(\random_bytes(4)),
            // 4 hex characters
            \bin2hex(\random_bytes(2)),
            // "4" for the UUID version + 3 hex characters
            // 0x4000 is 16384 in int
            // 0x4fff is 20479 in int
            \dechex(\random_int(16384, 20479)),
            // (8, 9, a, or b) for the UUID variant 1 plus 3 hex characters
            //  0x8000 is 32768 in int
            //  0xbfff is 49151 in int
            \dechex(\random_int(32768, 49151)),
            // 12 hex characters
            \bin2hex(\random_bytes(6))
        );
    }
}
