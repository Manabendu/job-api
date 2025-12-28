<?php
require_once "../config/env.php";
loadEnv(__DIR__ . "/../.env");

error_reporting(E_ALL);
ini_set('display_errors', 1);


// ✅ CORS HEADERS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight (OPTIONS request)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header("Content-Type: application/json");


require_once "../config/database.php";
require_once "../helpers/response.php";

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri  = explode("/", trim($path, "/"));


/*
 Example URL:
 /api/v1/jobs/1
*/

if (!isset($uri[0], $uri[1]) || $uri[0] !== "api" || $uri[1] !== "v1") {
    errorResponse(404, "Invalid API base path");
}

$resource = $uri[2] ?? null;

if ($resource === "jobs") {
    require "../routes/jobs.php";
} elseif ($resource === "auth") {
    require "../routes/auth.php";
} elseif ($resource === "admin") {
    require "../routes/admin.php";
} else {
    errorResponse(404, "Route not found");
}
