<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'driver_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role_id', 3)
                          ->whereNull('deleted_at');
                }),
            ],

            'vehicle_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('vehicles', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                }),
            ],

            'assigned_by' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role_id', 2)
                          ->whereNull('deleted_at');
                }),
            ],

            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after:start_date',

            'status' => 'sometimes|required|in:active,finished,cancelled',

            'observations' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'driver_id.exists' => 'El chofer seleccionado no existe o no es válido.',
            'vehicle_id.exists' => 'El vehículo no existe o está eliminado.',
            'assigned_by.exists' => 'El operador no es válido.',
        ];
    }
}