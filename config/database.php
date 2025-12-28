<?php

function db() {
    return new PDO(
        "mysql:host=localhost;dbname=job_portal",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
}
