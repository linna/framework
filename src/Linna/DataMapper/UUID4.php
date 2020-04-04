<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\DataMapper;

use InvalidArgumentException;

/**
 * Universally Unique Identifier Version 4 utility.
 * https://en.wikipedia.org/wiki/Universally_unique_identifier
 * https://tools.ietf.org/html/rfc4122
 *
 */
class UUID4
{
    /**
     * @var string UUID in hex form.
     */
    private string $hexUUID = '';

    /**
     * @var string UUID in bin form.
     */
    private string $binUUID = '';

    /**
     * Constructor.
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
     * Check UUID.
     *
     * @param string $uuid
     *
     * @return void
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
     * Get UUID in hex format (36 chars).
     *
     * @return string
     */
    public function getHex(): string
    {
        return $this->hexUUID;
    }

    /**
     * Get UUID in binary format (16 byte).
     *
     * @return string
     */
    public function getBin(): string
    {
        return $this->binUUID;
    }

    /**
     * Return UUID in 16 byte binary format.
     *
     * @return void
     */
    private function makeBin(): void
    {
        $this->binUUID = \hex2bin(\str_replace('-', '', $this->hexUUID));
    }

    /**
     * Generate a random UUID v4 in hex format.
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
