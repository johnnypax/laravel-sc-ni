<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Helpers\ScoreHelper;

class UserService
{
    public function __construct(
        protected UserRepository $userRepo
    ) {}

    public function calculateUserScore(int $id): int
    {
        $user = $this->userRepo->getUserById($id);
        if (!$user) {
            throw new \Exception("User not found");
        }

        return ScoreHelper::calculateScore($user['purchases'], $user['active']);
    }
}
