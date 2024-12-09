<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../routes/api.php';

$app->run();
