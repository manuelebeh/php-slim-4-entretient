<?php

namespace App\Models;

class User
{
    public int $id;
    public string $first_name;
    public string $last_name;
    public string $email;
    public string $created_at;
    public string $updated_at;

    public function __construct(int $id, string $first_name, string $last_name, string $email, string $created_at, string $updated_at)
    {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
