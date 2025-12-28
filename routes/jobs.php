<?php

require_once "../controllers/JobController.php";
require_once "../helpers/auth.php";

// IMPORTANT: define method here
$method = $_SERVER['REQUEST_METHOD'];

$controller = new JobController();

/*
 URL patterns:
 GET    /api/v1/jobs
 GET    /api/v1/jobs/{id}
 POST   /api/v1/jobs
 PUT    /api/v1/jobs/{id}
 PATCH  /api/v1/jobs/{id}
 DELETE /api/v1/jobs/{id}
*/

if ($method === "GET") {

    if (isset($uri[3]) && is_numeric($uri[3])) {
        // /api/v1/jobs/1
        $controller->show((int)$uri[3]);
    } else {
        // /api/v1/jobs
        $controller->index();
    }

} elseif ($method === "POST") {

    authRequired();
    $controller->store();

} elseif ($method === "PUT") {

    authRequired();

    if (!isset($uri[3])) {
        errorResponse(400, "Job ID is required");
    }

    $controller->update((int)$uri[3]);

} elseif ($method === "PATCH") {

    authRequired();

    if (!isset($uri[3])) {
        errorResponse(400, "Job ID is required");
    }

    $controller->patch((int)$uri[3]);

} elseif ($method === "DELETE") {

    authRequired();

    if (!isset($uri[3])) {
        errorResponse(400, "Job ID is required");
    }

    $controller->destroy((int)$uri[3]);

} else {
    errorResponse(405, "Method not allowed");
}
