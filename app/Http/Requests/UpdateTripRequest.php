<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTripRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'user_id' => 'sometimes|exists:users,id',
            'vehicle_id' => 'sometimes|exists:vehicles,id',
            'route_id' => 'sometimes|exists:routes,id',

            'departure_time' => 'sometimes|date',
            'return_time' => 'nullable|date|after_or_equal:departure_time',

            'km_departure' => 'sometimes|numeric|min:0',
            'km_return' => 'nullable|numeric|gte:km_departure',

            'observations' => 'nullable|string',
            'status' => 'sometimes|string|max:20',
        ];
    }
}
