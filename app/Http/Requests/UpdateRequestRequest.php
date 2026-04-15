<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|exists:users,id',
            'vehicle_id' => 'sometimes|exists:vehicles,id',

            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',

            'status' => 'sometimes|string|max:20',
            'observations' => 'nullable|string',

            'approved_by' => 'nullable|exists:users,id',
        ];
    }
}