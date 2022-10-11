<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2022, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Crypto;

/**
 * Public Key Cryptography Key Pair.
 *
 * <p>The key pair is composed by a public key and a secret key.</p>
 */
class KeyPair
{
    /**
     * Class Contructor.
     *
     * @param string $public The public key part of the key pair.
     * @param string $secret The secret key part of the key pair.
     */
    public function __construct(
        /** @var string The public key part of the key pair. */
        public readonly string $public,
        /** @var string The secret key part of the key pair. */
        public readonly string $secret
    ) {
    }
}
