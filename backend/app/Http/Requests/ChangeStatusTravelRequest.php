<?php

namespace App\Http\Requests;

use App\Enums\TravelStatus;

class ChangeStatusTravelRequest extends BaseFormRequest
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
            'status' => [
                'required',
                'integer',
                'in:' . implode(',', array_column(TravelStatus::cases(), 'value')),
            ],
        ];
    }
}
