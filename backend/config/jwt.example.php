<?php
define('JWT_SECRET', 'replace-with-a-long-random-secret');
define('JWT_EXPIRY', 86400); // 24 hours

function jwtEncode(array $payload): string {
    $header    = base64url_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $payload['exp'] = time() + JWT_EXPIRY;
    $payload   = base64url_encode(json_encode($payload));
    $signature = base64url_encode(hash_hmac('sha256', "$header.$payload", JWT_SECRET, true));
    return "$header.$payload.$signature";
}

function jwtDecode(string $token): ?array {
    $parts = explode('.', $token);
    if (count($parts) !== 3) return null;

    [$header, $payload, $signature] = $parts;

    $expected = base64url_encode(hash_hmac('sha256', "$header.$payload", JWT_SECRET, true));
    if (!hash_equals($expected, $signature)) return null;

    $data = json_decode(base64url_decode($payload), true);
    if (!$data || $data['exp'] < time()) return null;

    return $data;
}

function base64url_encode(string $data): string {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode(string $data): string {
    return base64_decode(strtr($data, '-_', '+/'));
}
