<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function getProductsByIds(array $ids): array
    {
        return Product::query()
            ->whereIn('id', $ids)
            ->get(['id','name','price'])
            ->map(fn($p) => [
                'id' => (int)$p->id,
                'name' => (string)$p->name,
                'price' => (float)$p->price,
            ])->all();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function all(): array
    {
        return Product::query()
            ->orderBy('id')
            ->get(['id','name','price'])
            ->map(fn($p) => [
                'id' => (int)$p->id,
                'name' => (string)$p->name,
                'price' => (float)$p->price,
            ])->all();
    }

    public function find(int $id): ?array
    {
        $p = Product::query()->find($id, ['id','name','price']);
        if (!$p) return null;
        return [
            'id' => (int)$p->id,
            'name' => (string)$p->name,
            'price' => (float)$p->price,
        ];
    }

    public function update(int $id, array $data): bool
    {
        $p = Product::query()->find($id);
        return $p ? $p->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $p = Product::query()->find($id);
        return $p ? (bool)$p->delete() : false;
    }
}
