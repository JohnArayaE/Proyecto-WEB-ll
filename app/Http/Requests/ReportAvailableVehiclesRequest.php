<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportAvailableVehiclesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // puedes luego meter roles si quieres
    }

    public function rules(): array
    {
        return [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ];
    }

    public function messages(): array
    {
        return [
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'end_date.after' => 'La fecha final debe ser mayor a la inicial.',
        ];
    }
}