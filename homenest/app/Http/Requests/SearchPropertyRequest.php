<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SearchPropertyRequest extends FormRequest
{
        public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'keyword'     => ['nullable', 'string', 'max:100'],
            'listing_kind'=> ['nullable', 'in:sale,rent'],
            'type_id'     => ['nullable', 'integer', 'exists:property_types,id'],
            'province_id' => ['nullable', 'integer', 'exists:provinces,id'],
            'district_id' => ['nullable', 'integer', 'exists:districts,id'],
            'price_min'   => ['nullable', 'numeric', 'min:0'],
            'price_max'   => ['nullable', 'numeric', 'min:0'],
            'area_min'    => ['nullable', 'numeric', 'min:0'],
            'area_max'    => ['nullable', 'numeric', 'min:0'],
            'bedrooms'    => ['nullable', 'integer', 'min:0', 'max:20'],
            'sort'        => ['nullable', 'in:newest,price_asc,price_desc'],
        ];
    }
}
