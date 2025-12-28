<?php

require_once "../config/jwt.php";

class AuthController {

    public function login() {

        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['email']) || empty($data['password'])) {
            errorResponse(400, "Email and password required");
        }

        // Find admin
        $stmt = db()->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$data['email']]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin || !password_verify($data['password'], $admin['password'])) {
            errorResponse(401, "Invalid credentials");
        }

        // Create JWT payload
        $payload = [
            "id" => $admin['id'],
            "email" => $admin['email'],
            "exp" => time() + JWT_EXPIRE
        ];

        $token = $this->generateJWT($payload);

        successResponse(
            $token,
            "Login successful",
            200
        );
    }

    private function generateJWT($payload) {

        $header = base64_encode(json_encode([
            "alg" => "HS256",
            "typ" => "JWT"
        ]));

        $body = base64_encode(json_encode($payload));

        $signature = hash_hmac(
            "sha256",
            "$header.$body",
            JWT_SECRET,
            true
        );

        $signature = base64_encode($signature);

        return "$header.$body.$signature";
    }
}
