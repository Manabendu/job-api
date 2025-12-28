<?php

define("JWT_SECRET", $_ENV['JWT_SECRET'] ?? getenv('JWT_SECRET'));
define("JWT_EXPIRE", (int)($_ENV['JWT_EXPIRE'] ?? getenv('JWT_EXPIRE') ?? 3600));
// var_dump(JWT_SECRET);
// var_dump(JWT_EXPIRE);
// exit;
