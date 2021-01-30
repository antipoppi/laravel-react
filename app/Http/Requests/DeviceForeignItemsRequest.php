<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceForeignItemsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        return [
            'customer_name' => ['required','exists:customers,name'],
            'user_name' => ['required','exists:users,name'],
            'operating_system_name' => 'unique:operating_systems'|'exists:operating_systems,name',
            'device_type_type' => 'unique:device_types'|'exists:device_types,type',
            'license_name' => ['exists:licenses,name'],
            'software_items' => ['exists:softwares,items'],
            'warranty_description' => ['exists:warranties,description'],
            'manufacturer_name' => ['required,exists:manufacturers,name'],
            'security_software_description' => ['exists:security_softwares,description'],
            'vendor_name' => ['exists:vendors,name'],
            'backup_solution_description' => ['exists:backup_solutions,description'],

        ];
    }

}
