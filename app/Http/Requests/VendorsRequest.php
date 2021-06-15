<?php

namespace App\Http\Requests;
use App\Http\Controllers\Admin\VendorsController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendorsRequest extends FormRequest
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
            'mobile' => 'required|max:100|unique:vendors,mobile',
            'email' => 'required|email|unique:vendors,email',
            'category_id' => 'required|exists:main_categories,id',
            'address' => 'required|string|max:500',
            'password' => 'required_without:id'
            // 'mobile' =>'required|max:100', Rule::unique('vendors')->ignore($this->id),
            // 'email'  => 'required|email', Rule::unique('vendors')->ignore($this->id),


        ];
    }

    public function messages()
    {
        return [
            'required' => 'لازم تملا الحقل دا',
            'logo.required_without' => 'لازم تختار لوجو',
            'mimes' => 'لازم الصورة تبقى jpg,jpeg,png',
            'name.string' => 'الإسم لازم حروف بس',
            'name.max' => 'الإسم لازم ميزدش عن 100 حرف',
            'ctegory_id.exists' => 'هذا الحقل غير موجود',
            'mobile.max' => 'لازم رقم الموبيل ميزدش عن 20',
            'email.email' => 'صيغة الإيميل مش صحيحة',
            'address.string' => 'العنوان لازم ميزدش عن 300 حرف',
            'mobile.unique' => 'المويبل دا متسجل قبل كدا',
            'email.unique' => 'الإيميل دا متسجل قبل كدا'


        ];

    }
}
