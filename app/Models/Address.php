<?php

namespace App\Models;

class Address
{
    protected int $id;
    protected string $user_id;
    protected string $street;
    protected string $city;
    protected string $country;
    protected string $postal_code;
    protected string $created_at;
    protected string $updated_at;

    public function __construct(int $id, string $user_id, string $street, string $city, string $country, string $postal_code, string $created_at, string $updated_at)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->street = $street;
        $this->city = $city;
        $this->country = $country;
        $this->postal_code = $postal_code;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getPostalCode(): string
    {
        return $this->postal_code;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
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

