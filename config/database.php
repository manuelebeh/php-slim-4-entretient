<?php

return [
    'driver' => $_ENV['DB_DRIVER'] ?? 'mysql',
    'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'database' => $_ENV['DB_NAME'] ?? '',
    'username' => $_ENV['DB_USER'] ?? '',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
    'charset' => $_ENV['DB_CHARSET'] ?? 'utf8',
    'collation' => $_ENV['DB_COLLATION'] ?? 'utf8mb3_general_ci',
    'prefix' => $_ENV['DB_PREFIX'] ?? ''
];