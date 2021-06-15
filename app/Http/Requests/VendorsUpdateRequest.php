<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorsUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'logo' => 'required_without:id|mimes:jpg,jpeg,png',
            'name' => 'required|string|max:100',
            'mobile' =>'required|max:100',
            'email'  => 'required|email',
            'category_id'  => 'required|exists:main_categories,id',
            'address'   => 'required|string|max:500',
            'password'   => 'required_without:id'
            // 'mobile' =>'required|max:100', Rule::unique('vendors')->ignore($this->id),
            // 'email'  => 'required|email', Rule::unique('vendors')->ignore($this->id),


        ];
    }
}
