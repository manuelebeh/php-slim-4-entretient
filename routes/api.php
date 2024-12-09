<?php

use App\Controllers\UserController;
use App\Controllers\AddressController;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->group('/users', function (RouteCollectorProxy $group) {
    $group->get('', [UserController::class, 'index']);
    $group->get('/{id}', [UserController::class, 'show']);
    $group->get('/{user_id}/addresses', AddressController::class . ':getAddressesByUserId');
    $group->get('/{user_id}/total-price', [AddressController::class, 'calculateTotalPriceForUser']);
    $group->post('', [UserController::class, 'create']);
    $group->put('/{id}', [UserController::class, 'update']);
    $group->delete('/{id}', [UserController::class, 'delete']);
});

$app->group('/addresses', function (RouteCollectorProxy $group) {
    $group->get('', [AddressController::class, 'index']);
    $group->get('/{id}', [AddressController::class, 'show']);
    $group->post('', [AddressController::class, 'create']);
    $group->put('/{id}', [AddressController::class, 'update']);
    $group->delete('/{id}', [AddressController::class, 'delete']);
});
