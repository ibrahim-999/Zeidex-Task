<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

final class TransactionReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => [
                'sometimes',
                'integer'
            ],
            'date_from' => [
                'sometimes',
                'date',
                'date_format:Y-m-d'
            ],
            'date_to' => [
                'sometimes',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:date_from'
            ],
        ];
    }
}
