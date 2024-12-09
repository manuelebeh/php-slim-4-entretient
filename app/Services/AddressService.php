<?php

namespace App\Services;

use App\Models\Address;
use PDO;
use App\Helpers\Utility;

class AddressService
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Récupérer toutes les adresses
    public function getAllAddresses(): array
    {
        $stmt = $this->db->query("SELECT id, user_id, street, city, postal_code, country, created_at, updated_at FROM addresses");
        $addresses = $stmt->fetchAll(PDO::FETCH_OBJ);

        return array_map(function ($addressData) {
            return new Address(
                $addressData->id,
                $addressData->user_id,
                $addressData->street,
                $addressData->city,
                $addressData->country,
                Utility::formatPostalCode($addressData->postal_code),
                $addressData->created_at,
                $addressData->updated_at
            );
        }, $addresses);
    }

    // Récupérer une adresse par son ID
    public function getAddressById(int $id): ?Address
    {
        $stmt = $this->db->prepare("SELECT id, user_id, street, city, postal_code, country, created_at, updated_at FROM addresses WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $addressData = $stmt->fetch(PDO::FETCH_OBJ);

        if ($addressData) {
            return new Address(
                $addressData->id,
                $addressData->user_id,
                $addressData->street,
                $addressData->city,
                $addressData->country,
                Utility::formatPostalCode($addressData->postal_code),
                $addressData->created_at,
                $addressData->updated_at
            );
        }

        return null;
    }

    // Créer une adresse
    public function createAddress(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO addresses (user_id, street, city, postal_code, country, created_at, updated_at)
            VALUES (:user_id, :street, :city, :postal_code, :country, NOW(), NOW())
        ");
        return $stmt->execute($data);
    }

    // Mettre à jour une adresse
    public function updateAddress(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE addresses 
            SET user_id = :user_id, street = :street, city = :city, postal_code = :postal_code, country = :country
            WHERE id = :id
        ");
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    // Supprimer une adresse
    public function deleteAddress(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM addresses WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Récupérer les adresses d'un utilisateur
    public function getAddressesByUserId(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT id, street, city, postal_code, country, created_at, updated_at FROM addresses WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $addressesData = $stmt->fetchAll(PDO::FETCH_OBJ);

        return array_map(function ($addressData) use ($userId) {
            return new Address(
                $addressData->id,
                $addressData->user_id ?? $userId,
                $addressData->street,
                $addressData->city,
                $addressData->postal_code,
                $addressData->country,
                $addressData->created_at,
                $addressData->updated_at
            );
        }, $addressesData);
    }

    public function calculateUserTotalPrice(int $userId): string
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as address_count FROM addresses WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        return Utility::calculateTotalPrice($result->address_count);
    }
}
