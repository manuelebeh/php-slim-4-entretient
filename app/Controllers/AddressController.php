<?php

namespace App\Controllers;

use App\Services\AddressService;
use App\Models\Address;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Contracts\ControllerInterface;

class AddressController implements ControllerInterface
{
    private AddressService $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    // Récupérer toutes les adresses
    public function index(Request $request, Response $response): Response
    {
        try {
            $addresses = $this->addressService->getAllAddresses();
            return $this->jsonResponse($response, $addresses);
        } catch (\Exception $e) {
            return $response->withStatus(500)->withJson(['error' => $e->getMessage()]);
        }
    }

    // Récupérer une adresse par ID
    public function show(Request $request, Response $response, array $args): Response
    {
        try {
            $address = $this->addressService->getAddressById((int)$args['id']);

            if ($address) {
                return $this->jsonResponse($response, $address);
            }

            return $response->withStatus(404, 'Address not found');
        } catch (\Exception $e) {
            return $response->withStatus(500)->withJson(['error' => $e->getMessage()]);
        }
    }

    // Créer une nouvelle adresse
    public function create(Request $request, Response $response): Response
    {
        try {
            $data = json_decode($request->getBody()->getContents(), true);

            if ($data === null) {
                $response->getBody()->write(json_encode(['error' => 'Invalid JSON provided']));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }

            $address = new Address(
                null,
                $data['user_id'] ?? null,
                $data['street'] ?? null,
                $data['city'] ?? null,
                $data['country'] ?? null,
                $data['postal_code'] ?? null,
                null,
                null
            );

            $created = $this->addressService->createAddress($address);

            if ($created) {
                $response->getBody()->write(json_encode(['message' => 'Address created successfully']));
                return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode(['error' => 'Failed to create address']));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    // Mettre à jour une adresse
    public function update(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int)$args['id'];
            $data = json_decode($request->getBody()->getContents(), true);

            if ($data === null) {
                $response->getBody()->write(json_encode(['error' => 'Invalid JSON provided']));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }

            $address = new Address(
                $id,
                $data['user_id'] ?? null,
                $data['street'] ?? null,
                $data['city'] ?? null,
                $data['country'] ?? null,
                $data['postal_code'] ?? null,
                null,
                null
            );

            $updated = $this->addressService->updateAddress($id, $address);

            if ($updated) {
                $response->getBody()->write(json_encode(['message' => 'Address updated successfully']));
                return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode(['error' => 'Address not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    // Supprimer une adresse
    public function delete(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int)$args['id'];
            $deleted = $this->addressService->deleteAddress($id);

            if ($deleted) {
                $response->getBody()->write(json_encode(['message' => 'Address deleted successfully']));
                return $response->withStatus(204)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode(['error' => 'Address not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    // Récupérer les adresses d'un utilisateur par son ID
    public function getAddressesByUserId(Request $request, Response $response, $args): Response
    {
        try {
            $userId = (int) $args['user_id'];
            $addresses = $this->addressService->getAddressesByUserId($userId);

            return $this->jsonResponse($response, $addresses);
        } catch (\Exception $e) {
            return $response->withStatus(500)->withJson(['error' => $e->getMessage()]);
        }
    }

    // Calculer le prix total pour les adresses d'un utilisateur
    public function calculateTotalPriceForUser(Request $request, Response $response, $args): Response
    {
        try {
            $userId = (int) $args['user_id'];
            $totalPrice = $this->addressService->calculateUserTotalPrice($userId);

            return $this->jsonResponse($response, ['total_price' => $totalPrice]);
        } catch (\Exception $e) {
            return $response->withStatus(500)->withJson(['error' => $e->getMessage()]);
        }
    }

    // Fonction utilitaire pour la réponse JSON
    private function jsonResponse(Response $response, $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
