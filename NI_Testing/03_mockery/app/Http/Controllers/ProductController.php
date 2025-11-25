<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(private ProductRepositoryInterface $repository) {}

    public function index(): JsonResponse
    {
        return response()->json($this->repository->all());
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $p = $this->repository->create($request->validated());
        return response()->json([
            'id' => $p->id,
            'name' => $p->name,
            'price' => (float)$p->price,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $p = $this->repository->find($id);
        if (!$p) return response()->json(['message' => 'Not Found'], 404);
        return response()->json($p);
    }

    public function update(StoreProductRequest $request, int $id): JsonResponse
    {
        $ok = $this->repository->update($id, $request->validated());
        if (!$ok) return response()->json(['message' => 'Not Found'], 404);
        return response()->json(['updated' => true]);
    }

    public function destroy(int $id): JsonResponse
    {
        $ok = $this->repository->delete($id);
        if (!$ok) return response()->json(['message' => 'Not Found'], 404);
        return response()->json(['deleted' => true], 200);
    }
}
