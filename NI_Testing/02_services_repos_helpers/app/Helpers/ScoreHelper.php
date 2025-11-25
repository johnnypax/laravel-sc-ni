<?php

namespace App\Helpers;

class ScoreHelper
{
    public static function calculateScore(int $purchases, bool $active): int
    {
        $base = $purchases * 10;
        return $active ? $base + 50 : $base;
    }
}
