<?php

namespace App\Models;

class Address
{
    public int $id;
    public string $user_id;
    public string $street;
    public string $city;
    public string $country;
    public string $postal_code;
    public string $created_at;
    public string $updated_at;

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
}

