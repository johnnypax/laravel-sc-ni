<?php

namespace Tests\Unit;

use App\Repositories\ProductRepositoryInterface;
use App\Services\OrderService;
use Mockery;
use PHPUnit\Framework\TestCase;

class OrderServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_calcola_totale_con_prodotti_mockati(): void
    {
        $repo = Mockery::mock(ProductRepositoryInterface::class);
        $repo->shouldReceive('getProductsByIds')
            ->once()
            ->with([1,2,3])
            ->andReturn([
                ['id'=>1,'name'=>'A','price'=>10.00],
                ['id'=>2,'name'=>'B','price'=>20.00],
                ['id'=>3,'name'=>'C','price'=>30.00],
            ]);

        $service = new OrderService($repo);
        $this->assertSame(60.00, $service->calculateTotal([1,2,3]));
    }

    public function test_lancia_eccezione_se_lista_vuota(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $service = new OrderService(Mockery::mock(ProductRepositoryInterface::class));
        $service->calculateTotal([]);
    }
}
