<?php

namespace App\Services;

use App\Models\User;
use PDO;

class UserService
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Récupérer tous les utilisateurs
    public function getAllUsers(): array
    {
        $stmt = $this->db->query("SELECT id, first_name, last_name, email, created_at, updated_at FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);

        return array_map(function ($userData) {
            return new User(
                $userData->id,
                $userData->first_name,
                $userData->last_name,
                $userData->email,
                $userData->created_at,
                $userData->updated_at
            );
        }, $users);
    }

    // Récupérer un utilisateur par son ID
    public function getUserById(int $id): ?User
    {
        $stmt = $this->db->prepare("SELECT id, first_name, last_name, email, created_at, updated_at FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $userData = $stmt->fetch(PDO::FETCH_OBJ);

        if ($userData) {
            return new User(
                $userData->id,
                $userData->first_name,
                $userData->last_name,
                $userData->email,
                $userData->created_at,
                $userData->updated_at
            );
        }

        return null;
    }

    // Créer un utilisateur
    public function createUser(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (first_name, last_name, email, created_at, updated_at)
            VALUES (:first_name, :last_name, :email, NOW(), NOW())
        ");
        return $stmt->execute($data);
    }

    // Mettre à jour un utilisateur
    public function updateUser(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET first_name = :first_name, last_name = :last_name, email = :email, updated_at = NOW()
            WHERE id = :id
        ");
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    // Supprimer un utilisateur
    public function deleteUser(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
