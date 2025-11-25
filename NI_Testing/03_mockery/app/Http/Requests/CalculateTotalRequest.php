<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalculateTotalRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'product_ids'   => ['required','array','min:1'],
            'product_ids.*' => ['integer','exists:products,id'],
        ];
    }
}
