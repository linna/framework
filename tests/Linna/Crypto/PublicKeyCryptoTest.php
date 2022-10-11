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

use PHPUnit\Framework\TestCase;

//use SodiumException;

/**
 * Public Key Crypto Test.
 *
 */
class PublicKeyCryptoTest extends TestCase
{
    /**
     * Test get key.
     *
     * @return void
     */
    public function testGenerateKey(): void
    {
        $keyPair = PublicKeyCrypto::generateKeyPair();

        $this->assertInstanceOf(KeyPair::class, $keyPair);
    }

    /**
     * Test get nonce.
     *
     * @return void
     */
    public function testGenerateNonce(): void
    {
        $this->assertSame(SODIUM_CRYPTO_BOX_NONCEBYTES, \strlen(PublicKeyCrypto::generateNonce()));
        $this->assertNotSame(PublicKeyCrypto::generateNonce(), PublicKeyCrypto::generateNonce());
    }

    /**
     * Test encrypt.
     *
     * @return void
     */
    public function testEncrypt(): void
    {
        $senderKeyPair = PublicKeyCrypto::generateKeyPair();
        $receiverKeyPair = PublicKeyCrypto::generateKeyPair();

        $this->assertInstanceOf(KeyPair::class, $senderKeyPair);
        $this->assertInstanceOf(KeyPair::class, $receiverKeyPair);

        $nonce = PublicKeyCrypto::generateNonce();

        $message = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, '
        . 'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

        $crypto = new PublicKeyCrypto();

        $cipertext = $crypto->encrypt($message, $nonce, $senderKeyPair->secret, $receiverKeyPair->public);

        $this->assertInstanceOf(PublicKeyCrypto::class, $crypto);
        $this->assertNotSame($message, $cipertext);
    }

    /**
     * Test decrypt.
     *
     * @return void
     */
    public function testDecrypt(): void
    {
        $senderKeyPair = PublicKeyCrypto::generateKeyPair();
        $receiverKeyPair = PublicKeyCrypto::generateKeyPair();

        $this->assertInstanceOf(KeyPair::class, $senderKeyPair);
        $this->assertInstanceOf(KeyPair::class, $receiverKeyPair);

        $nonce = PublicKeyCrypto::generateNonce();

        $message = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, '
        . 'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

        $crypto = new PublicKeyCrypto();

        $cipertext = $crypto->encrypt($message, $nonce, $senderKeyPair->secret, $receiverKeyPair->public);

        $this->assertInstanceOf(PublicKeyCrypto::class, $crypto);
        $this->assertNotSame($message, $cipertext);

        $plaintext = $crypto->decrypt($cipertext, $nonce, $senderKeyPair->public, $receiverKeyPair->secret);

        $this->assertSame($message, $plaintext);
    }
}
