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
use SodiumException;

/**
 * Secret Key Crypto Test.
 *
 */
class SecretKeyCryptoTest extends TestCase
{
    /**
     * Test get key.
     *
     * @return void
     */
    public function testGenerateKey(): void
    {
        $this->assertSame(32, \strlen(SecretKeyCrypto::generateKey()));
        $this->assertNotSame(SecretKeyCrypto::generateKey(), SecretKeyCrypto::generateKey());
    }

    /**
     * Test get nonce.
     *
     * @return void
     */
    public function testGenerateNonce(): void
    {
        if (SecretKeyCrypto::getAlgo() == "AES-256-GCM") {
            $this->assertSame(12, \strlen(SecretKeyCrypto::generateNonce()));
        } else {
            $this->assertSame(24, \strlen(SecretKeyCrypto::generateNonce()));
        }

        $this->assertNotSame(SecretKeyCrypto::generateNonce(), SecretKeyCrypto::generateNonce());
    }

    /**
     * Test encrypt.
     *
     * @return void
     */
    public function testEncrypt(): void
    {
        $key = SecretKeyCrypto::generateKey();
        $nonce = SecretKeyCrypto::generateNonce();
        $message = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, '
        . 'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
        $additional_data = 'Lorem Ipsum is simply dummy text';

        $crypto = new SecretKeyCrypto();

        $cipertext = $crypto->encrypt($message, $additional_data, $nonce, $key);

        $this->assertInstanceOf(SecretKeyCrypto::class, $crypto);
        $this->assertNotSame($message, $cipertext);
    }

    /**
     * Test decrypt.
     *
     * @return void
     */
    public function testDecrypt(): void
    {
        $key = SecretKeyCrypto::generateKey();
        $nonce = SecretKeyCrypto::generateNonce();
        $message = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, '
        . 'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
        $additional_data = 'Lorem Ipsum is simply dummy text';

        $crypto = new SecretKeyCrypto();

        $cipertext = $crypto->encrypt($message, $additional_data, $nonce, $key);

        $this->assertInstanceOf(SecretKeyCrypto::class, $crypto);
        $this->assertNotSame($message, $cipertext);

        $plaintext = $crypto->decrypt($cipertext, $additional_data, $nonce, $key);

        $this->assertSame($message, $plaintext);
    }

    /**
     * Test decrypt.
     *
     * @return void
     */
    public function testDecryptFails(): void
    {
        $key = SecretKeyCrypto::generateKey();
        $nonce = SecretKeyCrypto::generateNonce();
        $message = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, '
        . 'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
        $additional_data = 'Lorem Ipsum is simply dummy text';

        $crypto = new SecretKeyCrypto();

        $cipertext = $crypto->encrypt($message, $additional_data, $nonce, $key);

        $this->assertInstanceOf(SecretKeyCrypto::class, $crypto);
        $this->assertNotSame($message, $cipertext);

        $this->assertFalse($crypto->decrypt('foo bar baz', $additional_data, $nonce, $key));
        $this->assertFalse($crypto->decrypt($cipertext, 'foo bar baz', $nonce, $key));
    }

    /**
     * Test Invalid nonce length on encryption.
     *
     * @return void
     */
    public function testInvalidNonceLengthEncrypt(): void
    {
        $this->expectException(SodiumException::class);

        (new SecretKeyCrypto())->encrypt(
            'message as plaintext',
            'additional data',
            'invalid nonce', // must be 12 bytes for aes-256-gcm and 24 bytes for xchacha20
            SecretKeyCrypto::generateKey()
        );
    }

    /**
     * Test Invalid key length on encryption.
     *
     * @return void
     */
    public function testInvalidKeyLengthEncrypt(): void
    {
        $this->expectException(SodiumException::class);

        (new SecretKeyCrypto())->encrypt(
            'message as plaintext',
            'additional data',
            SecretKeyCrypto::generateNonce(),
            'invalid key' // must be 32 bytes
        );
    }

    /**
     * Test Invalid nonce length on decryption.
     *
     * @return void
     */
    public function testInvalidNonceLengthDecrypt(): void
    {
        $this->expectException(SodiumException::class);

        (new SecretKeyCrypto())->decrypt(
            'message as plaintext',
            'additional data',
            'invalid nonce', // must be 12 bytes for aes-256-gcm and 24 bytes for xchacha20
            SecretKeyCrypto::generateKey()
        );
    }

    /**
     * Test Invalid key length on decryption.
     *
     * @return void
     */
    public function testInvalidKeyLengthDecrypt(): void
    {
        $this->expectException(SodiumException::class);

        (new SecretKeyCrypto())->decrypt(
            'message as plaintext',
            'additional data',
            SecretKeyCrypto::generateNonce(),
            'invalid key' // must be 32 bytes
        );
    }
}
