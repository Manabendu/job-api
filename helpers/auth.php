<?php

require_once "../config/jwt.php";

function authRequired() {

    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        errorResponse(401, "Authorization header missing");
    }

    $token = str_replace("Bearer ", "", $headers['Authorization']);
    $parts = explode(".", $token);

    if (count($parts) !== 3) {
        errorResponse(401, "Invalid token");
    }

    [$header, $payload, $signature] = $parts;

    $validSignature = base64_encode(
        hash_hmac("sha256", "$header.$payload", JWT_SECRET, true)
    );

    if ($signature !== $validSignature) {
        errorResponse(401, "Token verification failed");
    }

    $data = json_decode(base64_decode($payload), true);

    if ($data['exp'] < time()) {
        errorResponse(401, "Token expired");
    }

    return $data; // admin info
}
