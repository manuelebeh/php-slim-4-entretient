<?php

namespace App\Controllers;

use App\Services\UserService;
use App\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Contracts\ControllerInterface;

class UserController implements ControllerInterface
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

            // Vérifiez si les données sont valides
            if ($data === null) {
                $response->getBody()->write(json_encode(['error' => 'Invalid JSON provided']));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }

            if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email'])) {
                $response->getBody()->write(json_encode(['error' => 'Missing required fields']));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }

            // Créez un nouvel utilisateur
            $user = new User(
                null, 
                $data['first_name'], 
                $data['last_name'], 
                $data['email']
            );

            $isCreated = $this->userService->createUser($user);

            if ($isCreated) {
                $responseData = [
                    'message' => 'User created successfully',
                    'data' => [
                        'first_name' => $user->getFirstName(),
                        'last_name' => $user->getLastName(),
                        'email' => $user->getEmail(),
                    ]
                ];
                $response->getBody()->write(json_encode($responseData));
                return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode(['error' => 'User creation failed']));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    // Mettre à jour un utilisateur
    public function update(Request $request, Response $response, array $args): Response
    {
        try {
            $data = json_decode($request->getBody()->getContents(), true);

            // Vérifiez si les données sont valides
            if ($data === null) {
                $response->getBody()->write(json_encode(['error' => 'Invalid JSON provided']));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }

            if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email'])) {
                $response->getBody()->write(json_encode(['error' => 'Missing required fields']));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }

            // Récupérez l'utilisateur existant avec l'ID passé dans l'URL
            $user = new User(
                (int)$args['id'], 
                $data['first_name'], 
                $data['last_name'], 
                $data['email']
            );

            $isUpdated = $this->userService->updateUser($user);

            if ($isUpdated) {
                $responseData = [
                    'message' => 'User updated successfully',
                    'data' => [
                        'id' => $user->getId(),
                        'first_name' => $user->getFirstName(),
                        'last_name' => $user->getLastName(),
                        'email' => $user->getEmail(),
                    ]
                ];
                $response->getBody()->write(json_encode($responseData));
                return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode(['error' => 'User not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
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
