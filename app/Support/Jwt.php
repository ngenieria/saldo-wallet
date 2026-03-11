<?php

namespace App\Support;

class Jwt
{
    public static function encode(array $payload, string $secret, string $alg = 'HS256'): string
    {
        $header = ['typ' => 'JWT', 'alg' => $alg];
        $segments = [];
        $segments[] = self::urlsafeB64Encode(json_encode($header));
        $segments[] = self::urlsafeB64Encode(json_encode($payload));
        $signingInput = implode('.', $segments);
        $signature = self::sign($signingInput, $secret, $alg);
        $segments[] = self::urlsafeB64Encode($signature);
        return implode('.', $segments);
    }

    public static function decode(string $jwt, string $secret, array $allowedAlgs = ['HS256']): ?array
    {
        $tks = explode('.', $jwt);
        if (count($tks) != 3) {
            return null;
        }
        list($headb64, $bodyb64, $cryptob64) = $tks;
        $header = json_decode(self::urlsafeB64Decode($headb64), true);
        if (!isset($header['alg']) || !in_array($header['alg'], $allowedAlgs, true)) {
            return null;
        }
        $payload = json_decode(self::urlsafeB64Decode($bodyb64), true);
        $sig = self::urlsafeB64Decode($cryptob64);
        $valid = hash_equals($sig, self::sign("$headb64.$bodyb64", $secret, $header['alg']));
        if (!$valid) {
            return null;
        }
        if (isset($payload['exp']) && time() >= $payload['exp']) {
            return null;
        }
        return $payload;
    }

    protected static function sign(string $msg, string $key, string $alg): string
    {
        switch ($alg) {
            case 'HS256':
                return hash_hmac('sha256', $msg, $key, true);
            case 'HS384':
                return hash_hmac('sha384', $msg, $key, true);
            case 'HS512':
                return hash_hmac('sha512', $msg, $key, true);
            default:
                throw new \UnexpectedValueException('Algorithm not supported');
        }
    }

    protected static function urlsafeB64Encode(string $input): string
    {
        return rtrim(strtr(base64_encode($input), '+/', '-_'), '=');
    }

    protected static function urlsafeB64Decode(string $input): string
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }
}

