<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePropertyRequest extends FormRequest
{
    
    public function authorize(): bool { return true; }   

    public function rules(): array
    {
        $flag = $this->input('listing_kind') === 'sale' ? 'for_sale' : 'for_rent';
        return [
            'title'        => ['required', 'string', 'max:255'],
            'listing_kind' => ['required', 'in:sale,rent'],
            'type_id'      => ['required', Rule::exists('property_types', 'type_id')->where($flag, true)],
            'district_id'  => ['required', 'exists:districts,district_id'],
            'price'        => ['required', 'numeric', 'min:1'],
            'area'         => ['required', 'numeric', 'min:1'],
            'bedrooms'     => ['nullable', 'integer', 'min:0'],
            'bathrooms'    => ['nullable', 'integer', 'min:0'],
            'address'      => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'latitude'     => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'    => ['nullable', 'numeric', 'between:-180,180'],
            'images'       => ['required', 'array', 'min:1', 'max:10'],
            'images.*'     => ['image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'cover'        => ['nullable', 'integer', 'min:0'],
        ];
    }
/*    public function messages(): array
{
    return [
        'type_id.required' => 'Vui lòng chọn loại bất động sản.',
        'type_id.exists' => 'Loại bất động sản không hợp lệ.',

        'district_id.required' => 'Vui lòng chọn quận/huyện.',
        'district_id.exists' => 'Quận/huyện không hợp lệ.',

        'price.required' => 'Vui lòng nhập giá bất động sản.',
        'price.numeric' => 'Giá phải là số hợp lệ.',
    ];
}
*/
}
