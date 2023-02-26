<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2022, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Crypto;

/**
 * Public Key Cryptography provider.
 *
 * <p>A easy to use wrapper to sodium authenticated encryption, it uses <code>sodium_crypto_box</code>.</p>
 *
 * @link https://www.php.net/manual/en/function.sodium-crypto-box.php
 * @link https://doc.libsodium.org/public-key_cryptography/authenticated_encryption
 */
class PublicKeyCrypto
{
    /**
     * Generate a key pair to encrypt or decrypt messages.
     *
     * @return KeyPair The key pair.
     */
    public static function generateKeyPair(): KeyPair
    {
        $keypair = \sodium_crypto_box_keypair();

        return new KeyPair(\sodium_crypto_box_publickey($keypair), \sodium_crypto_box_secretkey($keypair));
    }

    /**
     * Generate a nonce.
     *
     * @return string The nonce, 24 bytes long.
     */
    public static function generateNonce(): string
    {
        return \random_bytes(SODIUM_CRYPTO_BOX_NONCEBYTES);
    }

    /**
     * Encrypt a message using the secret key of the sender and the public key of the receiver.
     *
     * <p>To encrypt, the sender must have the public key of the receiver.</p>
     *
     *
     * @param string $message           The plain text message which will be encrypted.
     * @param string $nonce             A number that must be only used once, per message. Use the static method
     *                                  <code>generateNonce()</code> to generate a nonce having the correct size.
     * @param string $senderSecretKey   The secret key which belongs to the sender of the message.
     * @param string $receiverPublicKey The public key which belongs to the receiver of the message.
     *
     * @return string The ciphertext for the provided plain text.
     */
    public function encrypt(string $message, string $nonce, string $senderSecretKey, string $receiverPublicKey): string
    {
        return \sodium_crypto_box(
            $message,
            $nonce,
            \sodium_crypto_box_keypair_from_secretkey_and_publickey($senderSecretKey, $receiverPublicKey)
        );
    }

    /**
     * Decrypt a ciphertext using the secret key of the receiver and the public key of the sender.
     *
     * <p>To decrypt, the receiver must have the public key of the sender.</p>
     *
     * @param string $ciphertext        The encrypted message to attempt to decrypt.
     * @param string $nonce             A number that must be only used once, per message. Use the static method
     *                                  <code>generateNonce()</code> to generate a nonce having the correct size.
     * @param string $senderPublicKey   The public key which belongs to the sender of the message.
     * @param string $receiverSecretKey The secret key which belongs to the receiver of the message.
     *
     * @return string|false The original message, false if the decryption fails.
     */
    public function decrypt(string $ciphertext, string $nonce, string $senderPublicKey, string $receiverSecretKey): string|false
    {
        return \sodium_crypto_box_open(
            $ciphertext,
            $nonce,
            \sodium_crypto_box_keypair_from_secretkey_and_publickey($receiverSecretKey, $senderPublicKey)
        );
    }
}
