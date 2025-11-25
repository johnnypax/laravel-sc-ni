<?php

namespace App\Repositories;

use App\Models\Product;

interface ProductRepositoryInterface
{
    /** @return array<int, array{id:int,name:string,price:float}> */
    public function getProductsByIds(array $ids): array;

    public function create(array $data): Product;

    /** @return array<int, array{id:int,name:string,price:float}> */
    public function all(): array;

    public function find(int $id): ?array;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
