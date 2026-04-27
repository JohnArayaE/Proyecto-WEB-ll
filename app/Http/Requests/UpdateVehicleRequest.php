<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
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
        $vehicleId = $this->route('vehicle');

        return [
        'plate' => [
            'required',
            'string',
            'max:20',
            Rule::unique('vehicles', 'plate')->ignore($this->route('vehicle'))
        ],
        'brand' => 'required|string|max:50',
        'model' => 'required|string|max:50',
        'year' => 'required|integer|min:1970|max:' . date('Y'),
        'type' => 'nullable|string|max:50',
        'capacity' => 'nullable|integer|min:1',
        'fuel_type' => 'nullable|string|max:50',
        'image' => 'nullable|string|max:255',
        'status' => 'required|in:available,unavailable,under maintenance',
    ];
    }
}
