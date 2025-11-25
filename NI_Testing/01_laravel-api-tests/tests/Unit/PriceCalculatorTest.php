<?php

namespace Tests\Unit;

use App\Domain\PriceCalculator;
use PHPUnit\Framework\TestCase;

class PriceCalculatorTest extends TestCase
{
    public function test_final_price_with_discount_and_vat(): void
    {
        $calc = new PriceCalculator();
        $final = $calc->finalPrice(base: 100, discountPercent: 10, vatPercent: 22);

        // 100 - 10% = 90; 90 + 22% = 109.8
        $this->assertSame(109.8, $final);
    }

    public function test_final_price_no_discount(): void
    {
        $calc = new PriceCalculator();
        $final = $calc->finalPrice(base: 50, discountPercent: 0, vatPercent: 22);

        // 50 + 22% = 61.0
        $this->assertSame(61.0, $final);
    }
}
