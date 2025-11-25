<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculateTotalRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(private OrderService $service) {}

    public function calculate(CalculateTotalRequest $request): JsonResponse
    {
        $total = $this->service->calculateTotal($request->validated('product_ids'));
        return response()->json(['total' => $total], 200);
    }
}
