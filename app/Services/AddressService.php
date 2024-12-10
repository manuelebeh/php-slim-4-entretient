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
        $stmt = $this->db->query("
            SELECT id, user_id, street, city, postal_code, country, created_at, updated_at
            FROM addresses
        ");
        $addresses = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $this->mapAddresses($addresses);
    }

    // Récupérer une adresse par son ID
    public function getAddressById(int $id): ?Address
    {
        $stmt = $this->db->prepare("
            SELECT id, user_id, street, city, postal_code, country, created_at, updated_at
            FROM addresses
            WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);
        $addressData = $stmt->fetch(PDO::FETCH_OBJ);

        return $addressData ? $this->mapAddress($addressData) : null;
    }

    // Créer une adresse
    public function createAddress(Address $address): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO addresses (user_id, street, city, postal_code, country, created_at, updated_at)
            VALUES (:user_id, :street, :city, :postal_code, :country, NOW(), NOW())
        ");

        return $stmt->execute([
            'user_id' => $address->getUserId(),
            'street' => $address->getStreet(),
            'city' => $address->getCity(),
            'postal_code' => $address->getPostalCode(),
            'country' => $address->getCountry(),
        ]);
    }

    // Mettre à jour une adresse
    public function updateAddress(int $id, Address $address): bool
    {
        $stmt = $this->db->prepare("
            UPDATE addresses
            SET user_id = :user_id,
                street = :street,
                city = :city,
                postal_code = :postal_code,
                country = :country,
                updated_at = NOW()
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'user_id' => $address->getUserId(),
            'street' => $address->getStreet(),
            'city' => $address->getCity(),
            'postal_code' => $address->getPostalCode(),
            'country' => $address->getCountry(),
        ]);
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
        $stmt = $this->db->prepare("
            SELECT id, street, city, postal_code, country, created_at, updated_at
            FROM addresses
            WHERE user_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        $addressesData = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $this->mapAddresses($addressesData);
    }

    // Calculer le prix total pour un utilisateur (exemple fictif basé sur le nombre d'adresses)
    public function calculateUserTotalPrice(int $userId): string
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS address_count
            FROM addresses
            WHERE user_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        return Utility::calculateTotalPrice($result->address_count);
    }

    // Mapper une seule adresse
    private function mapAddress(object $addressData): Address
    {
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

    // Mapper plusieurs adresses
    private function mapAddresses(array $addressesData): array
    {
        return array_map([$this, 'mapAddress'], $addressesData);
    }
}
