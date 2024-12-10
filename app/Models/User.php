<?php

namespace App\Models;

class User
{
    protected int $id;
    protected string $first_name;
    protected string $last_name;
    protected string $email;
    protected string $created_at;
    protected string $updated_at;

    public function __construct(int $id, string $first_name, string $last_name, string $email, string $created_at, string $updated_at)
    {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function getEmail(): string
    {
        return $this->email;
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
    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setUpdatedAt(string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}
