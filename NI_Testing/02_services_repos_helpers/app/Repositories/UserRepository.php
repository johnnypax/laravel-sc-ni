<?php

namespace App\Repositories;

class UserRepository
{
    public function getUserById(int $id): ?array
    {
        return [
            'id' => $id,
            'name' => 'Mario Rossi',
            'purchases' => 10,
            'active' => true,
        ];
    }
}
