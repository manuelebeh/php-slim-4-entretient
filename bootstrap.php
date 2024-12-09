<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use App\Database\Connection;

require __DIR__ . '/vendor/autoload.php';

// Créer un builder pour le conteneur DI
$containerBuilder = new ContainerBuilder();

// Load database configuration
$config = require __DIR__ . '/config/database.php';

// Configurer la connexion PDO
$pdo = Connection::make($config);

// Construire le conteneur DI
$container = $containerBuilder->build();

// Ajouter la connexion PDO au conteneur
$container->set('db', function () use ($pdo) {
    return $pdo;
});

// Définir un service UserService dans le conteneur
$container->set(App\Services\UserService::class, function ($container) {
    return new App\Services\UserService($container->get('db'));
});

// Définir un contrôleur UserController dans le conteneur
$container->set(App\Controllers\UserController::class, function ($container) {
    return new App\Controllers\UserController($container->get(App\Services\UserService::class));
});

// Définir un service AddressService dans le conteneur
$container->set(App\Services\AddressService::class, function ($container) {
    return new App\Services\AddressService($container->get('db'));
});

// Définir un contrôleur AddressController dans le conteneur
$container->set(App\Controllers\AddressController::class, function ($container) {
    return new App\Controllers\AddressController($container->get(App\Services\AddressService::class));
});

// Configurer le conteneur pour Slim
AppFactory::setContainer($container);

// Créer l'application Slim
$app = AppFactory::create();

// Ajouter le middleware d'erreurs
$app->addErrorMiddleware(true, true, true);

return $app;
