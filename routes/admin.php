<?php

require_once "../controllers/AdminController.php";
require_once "../helpers/auth.php";

$method = $_SERVER['REQUEST_METHOD'];
$controller = new AdminController();

if ($method === "GET" && ($uri[3] ?? "") === "dashboard") {
    $controller->dashboard();
} else {
    errorResponse(404, "Admin endpoint not found");

}
