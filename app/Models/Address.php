<?php

namespace App\Models;

class Address implements \JsonSerializable
{
    public function __construct(
        private ?int $id = null,
        private ?int $user_id = null,
        private ?string $street = null,
        private ?string $city = null,
        private ?string $country = null,
        private ?string $postal_code = null,
        private ?string $created_at = null,
        private ?string $updated_at = null
    ) {}

    // Implémentation de JsonSerializable
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'street' => $this->street,
            'city' => $this->city,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    // Setters
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function setPostalCode(string $postal_code): void
    {
        $this->postal_code = $postal_code;
    }

    public function setUpdatedAt(string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}
