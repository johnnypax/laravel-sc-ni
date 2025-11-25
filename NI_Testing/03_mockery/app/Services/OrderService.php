<?php

namespace App\Services;

use App\Repositories\ProductRepositoryInterface;
use InvalidArgumentException;

class OrderService
{
    public function __construct(private ProductRepositoryInterface $repository)
    {
    }

    public function calculateTotal(array $productIds): float
    {
        if (empty($productIds)) {
            throw new InvalidArgumentException('No products provided');
        }

        $products = $this->repository->getProductsByIds($productIds);
        return round(collect($products)->sum('price'), 2);
    }
}
