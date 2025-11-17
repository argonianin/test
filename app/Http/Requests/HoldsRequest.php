<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HoldsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:holds,id',
        ];
    }
}
