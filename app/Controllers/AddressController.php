<?php

namespace App\Controllers;

use App\Services\AddressService;
use App\Models\Address;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddressController
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
                return $response->withStatus(400);
            }

            $address = $this->addressService->createAddress($data);

            return $this->jsonResponse($response, $address, 201);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
            ]));
            return $response->withStatus(500);
        }
    }

    // Mettre à jour une adresse
    public function update(Request $request, Response $response, array $args): Response
    {
        try {
            $data = json_decode($request->getBody()->getContents(), true);
            $address = $this->addressService->updateAddress((int)$args['id'], $data);

            if ($address) {
                return $this->jsonResponse($response, $address);
            }

            return $response->withStatus(404, 'Address not found');
        } catch (\Exception $e) {
            return $response->withStatus(500)->withJson(['error' => $e->getMessage()]);
        }
    }

    // Supprimer une adresse
    public function delete(Request $request, Response $response, array $args): Response
    {
        try {
            $deleted = $this->addressService->deleteAddress((int)$args['id']);

            if ($deleted) {
                return $response->withStatus(204);
            }

            return $response->withStatus(404, 'Address not found');
        } catch (\Exception $e) {
            return $response->withStatus(500)->withJson(['error' => $e->getMessage()]);
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
