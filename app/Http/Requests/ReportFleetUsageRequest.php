<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ReportFleetUsageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ];
    }
    protected function passedValidation(): void
    {
        $this->merge([
            'start_date' => Carbon::parse($this->start_date)->startOfDay(),
            'end_date' => Carbon::parse($this->end_date)->endOfDay(),
        ]);
    }
    public function messages(): array
    {
        return [
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'start_date.date' => 'La fecha de inicio debe ser válida.',

            'end_date.required' => 'La fecha final es obligatoria.',
            'end_date.date' => 'La fecha final debe ser válida.',
            'end_date.after' => 'La fecha final debe ser mayor a la inicial.',
        ];
    }
}