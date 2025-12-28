<?php

require_once "../controllers/AuthController.php";

$method = $_SERVER['REQUEST_METHOD'];
$controller = new AuthController();

if ($method === "POST") {
    $controller->login();
} else {
    if (!$token) {
    errorResponse(
        401,
        "Authorization token missing"
    );
}
}
