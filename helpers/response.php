<?php

function successResponse($data = null, $message = "Success", $httpStatus = 200)
{
    http_response_code($httpStatus);

    echo json_encode([
        "status" => $httpStatus,
        "message" => $message,
        "data" => $data
    ]);

    exit;
}

function errorResponse($httpStatus = 400, $message = "Error")
{
    http_response_code($httpStatus);

    echo json_encode([
        "status" => $httpStatus,
        "message" => $message,
        "data" => null
    ]);

    exit;
}
