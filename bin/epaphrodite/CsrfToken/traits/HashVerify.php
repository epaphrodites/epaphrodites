<?php

declare(strict_types=1);

namespace Epaphrodite\epaphrodite\CsrfToken\traits;

trait HashVerify
{
    /**
     * Verifies if two hash values are identical.
     *
     * @param string $hashedValue The hash to verify.
     * @param string $inputToken The input hash to compare.
     * @param string $secureToken The secure hash to compare.
     * @return bool True if the hashes are identical, otherwise false.
     */
    public static function verifyHashes(?string $hashedValue = '', ?string $inputToken = '', ?string $secureToken = ''): bool
    {

        return hash_equals($hashedValue, $inputToken) && hash_equals($hashedValue, $secureToken) && hash_equals($inputToken, $secureToken);
    }

    /**
     * Verifies if two input hashes are identical.
     *
     * @param string $hashedInput The first input hash to verify.
     * @param string $hashedValue The second input hash to compare.
     * @return bool True if the input hashes are identical, otherwise false.
     */
    public static function verifyInputHashes(?string $hashedInput = '', ?string $hashedValue = '' ): bool
    {
        return hash_equals($hashedInput, $hashedValue);
    }

    /**
     * Generic function to generate a hash using the GOST algorithm.
     *
     * @param string $data The data to hash.
     * @return string The generated hash.
     */
    public function gostHash(?string $data = null):string
    {
        return !empty($data) ? hash('gost', $data) : '';
    }    
}

