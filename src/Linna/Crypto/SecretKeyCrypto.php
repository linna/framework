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

use Closure;
use SodiumException;

/**
 * Secret Key Cryptography provider.
 *
 * <p>A easy to use wrapper to sodium AEAD constructions, it uses AES-256-GCM if
 * available, XChaCha20-Poly1305 otherwise.</p>
 *
 * <p>The implementation do not permit to choose the algorithm used. If one of them used in this class will be
 * insecure, it will be replaced.<p>
 *
 * @link https://www.php.net/manual/en/ref.sodium.php
 * @link https://doc.libsodium.org/secret-key_cryptography/aead
 */
class SecretKeyCrypto
{
    /** @var Closure Reference to the encryption function. */
    private Closure $encrypt;

    /** @var Closure Reference to the decryption function. */
    private Closure $decrypt;

    /**
     * Class Constructor.
     */
    public function __construct()
    {
        // get function reference for xchacha20-poly1305
        $this->encrypt = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt(...);
        $this->decrypt = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt(...);

        // if aes 256 gcm is available, use it
        if (\sodium_crypto_aead_aes256gcm_is_available()) {
            // get function reference for aes256gcm
            $this->encrypt = sodium_crypto_aead_aes256gcm_encrypt(...);
            $this->decrypt = sodium_crypto_aead_aes256gcm_decrypt(...);
        }
    }

    /**
     * Decrypt a ciphertext.
     *
     * @param string $ciphertext      Encrypted text in the format provided by the encryption function used (ciphertext
     *                                and tag, concatenated).
     * @param string $additional_data Additional, authenticated data. This is used in the verification of the
     *                                authentication tag appended to the ciphertext, but it is not encrypted or stored
     *                                in the ciphertext.
     * @param string $nonce           A number that must be only used once, per message. Use the static method
     *                                <code>generateNonce()</code> to generate a nonce having the correct size.
     * @param string $key             The key used to encrypt. Use the static method <code>generateKey()</code> to
     *                                generate a key having the correct size.
     *
     * @return string|false The original message, false if the decryption fails.
     *
     * @throws SodiumException If the <code>$nonce</code> or the <code>$key</code> have the wrong size.
     */
    public function decrypt(string $ciphertext, string $additional_data, string $nonce, string $key): string|false
    {
        // It isn't possible call the Closure using the property without using round brackets.
        return ($this->decrypt)($ciphertext, $additional_data, $nonce, $key);
    }

    /**
     * Encrypt a plain text.
     *
     * @param string $message         The plain text message which will be encrypted.
     * @param string $additional_data Additional, authenticated data. This is used in the verification of the
     *                                authentication tag appended to the ciphertext, but it is not encrypted or stored
     *                                in the ciphertext.
     * @param string $nonce           A number that must be only used once, per message. Use the static method
     *                                <code>generateNonce()</code> to generate a nonce having the correct size.
     * @param string $key             The key used to encrypt. Use the static method <code>generateKey()</code> to
     *                                generate a key having the correct size.
     *
     * @return string The ciphertext for the provided plain text.
     *
     * @throws SodiumException If the <code>$nonce</code> or the <code>$key</code> have the wrong size.
     */
    public function encrypt(string $message, string $additional_data, string $nonce, string $key): string
    {
        // It isn't possible call the Closure using the property without using round brackets.
        return ($this->encrypt)($message, $additional_data, $nonce, $key);
    }

    /**
     * Return the algorithms currently used to encrypt.
     *
     * @return string The algo.
     */
    public static function getAlgo(): string
    {
        return (\sodium_crypto_aead_aes256gcm_is_available()) ? 'AES-256-GCM' : 'XChaCha20-Poly1305-IETF';
    }

    /**
     * Generate a key to encrypt or decrypt.
     *
     * <p>The key is valid for the algorithm used by this class. Internally uses
     * <code>sodium_crypto_aead_aes256gcm_keygen</code> if AES-256-GCM is available else
     * <code>sodium_crypto_aead_xchacha20poly1305_ietf_keygen</code> is used.
     *
     * @return string The key.
     */
    public static function generateKey(): string
    {
        return (\sodium_crypto_aead_aes256gcm_is_available()) ?
        \sodium_crypto_aead_aes256gcm_keygen() :
        \sodium_crypto_aead_xchacha20poly1305_ietf_keygen();
    }

    /**
     * Generate a nonce to encrypt or decrypt.
     *
     * <p>The nonce is valid for the algorithm used by this class. Internally uses <code>random_bytes</code> to
     * generate the nonce.</p>
     *
     * @return string The nonce.
     */
    public static function generateNonce(): string
    {
        // 12 bytes for aes nonce
        // 24 bytes for xchacha nonce
        return (\sodium_crypto_aead_aes256gcm_is_available()) ?
        \random_bytes(SODIUM_CRYPTO_AEAD_AES256GCM_NPUBBYTES) :
        \random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);
    }
}
