<?php

namespace App\Domain;

class PriceCalculator
{
    public function finalPrice(float $base, float $discountPercent = 0, float $vatPercent = 22): float
    {
        $discounted = $base * (1 - $discountPercent / 100);
        $withVat = $discounted * (1 + $vatPercent / 100);
        return round($withVat, 2);
    }
}
