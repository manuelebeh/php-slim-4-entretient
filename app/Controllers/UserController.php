<?php

namespace App\Controllers;

use App\Services\UserService;
use App\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // Récupérer tous les utilisateurs
    public function index(Request $request, Response $response): Response
    {
        try {
            $users = $this->userService->getAllUsers();
            return $this->jsonResponse($response, $users);
        } catch (\Exception $e) {
            return $response->withStatus(500)->withJson(['error' => $e->getMessage()]);
        }
    }

    // Récupérer un utilisateur par ID
    public function show(Request $request, Response $response, array $args): Response
    {
        try {
            $user = $this->userService->getUserById((int)$args['id']);

            if ($user) {
                return $this->jsonResponse($response, $user);
            }

            return $response->withStatus(404, 'User not found');
        } catch (\Exception $e) {
            return $response->withStatus(500)->withJson(['error' => $e->getMessage()]);
        }
    }

    // Créer un nouvel utilisateur
    public function create(Request $request, Response $response): Response
    {
        try {
            $data = json_decode($request->getBody()->getContents(), true);

            if ($data === null) {
                return $response->withStatus(400)->write(json_encode(['error' => 'Invalid JSON provided']));
            }

            if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email'])) {
                return $response->withStatus(400)->write(json_encode(['error' => 'Missing required fields']));
            }

            $user = $this->userService->createUser($data);

            return $this->jsonResponse($response, $user, 201);

        } catch (\Exception $e) {
            return $response->withStatus(500)->write(json_encode([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
            ]));
        }
    }

    // Mettre à jour un utilisateur
    public function update(Request $request, Response $response, array $args): Response
    {
        try {
            $data = json_decode($request->getBody()->getContents(), true);
    
            if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email'])) {
                $response->getBody()->write(json_encode(['error' => 'Missing required fields']));
                return $response->withStatus(400);
            }
    
            $user = $this->userService->updateUser((int)$args['id'], $data);
    
            if ($user) {
                return $this->jsonResponse($response, $user);
            }
    
            $response->getBody()->write(json_encode(['error' => 'User not found']));
            return $response->withStatus(404);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
            ]));
            return $response->withStatus(500);
        }
    }

    // Supprimer un utilisateur
    public function delete(Request $request, Response $response, array $args): Response
    {
        try {
            $deleted = $this->userService->deleteUser((int)$args['id']);

            if ($deleted) {
                return $response->withStatus(204);
            }

            return $response->withStatus(404, 'User not found');
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
