<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap.php';

require __DIR__ . '/../database/migrations/001_create_users_table.php';
require __DIR__ . '/../database/migrations/002_create_addresses_table.php';

echo "Migrations exécutées avec succès !";
