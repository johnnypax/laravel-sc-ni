<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_calcola_totale_via_api(): void
    {
        $p1 = Product::factory()->create(['price' => 10.00]);
        $p2 = Product::factory()->create(['price' => 5.50]);

        $res = $this->postJson('/api/orders/calculate', [
            'product_ids' => [$p1->id, $p2->id],
        ]);

        $res->assertOk()
            ->assertJson(['total' => 15.50]);
    }

    public function test_validation_error_se_ids_mancano(): void
    {
        $this->postJson('/api/orders/calculate', [])
            ->assertStatus(422);
    }
}
