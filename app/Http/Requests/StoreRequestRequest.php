<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',

            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',

            'status' => 'nullable|string|max:20',
            'observations' => 'nullable|string',

            'approved_by' => 'nullable|exists:users,id',
        ];
    }
}