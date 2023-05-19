<?php
namespace RCare\Rpm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceOrderPlaceRequest extends FormRequest
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
       // return validationRules(True,
        return    [            
                'patient_id'        => 'required',
                'fname'             => 'required',
                'lname'             => 'required',
                'dob'               => 'required',
                'mob'               => 'required',
                'add_1'             => 'required',
                'gender'            => 'required',
                'city'              => 'required|min:3',
                'state'             => 'required',
                'zipcode'           => 'required|min:5|max:5',
                'provider_id'       => 'required',
                //'officelocation'    => 'required',
                'family_fname'      => 'required',
                'family_lname'      => 'required',
              //  'family_add'      => 'required',
                'family_mob'        => 'required',
                'phone_type'        => 'required',
                'email'             => 'required',
                'Relationship'      => 'required',          
                'ordertype'         => 'required',
                'shipping_option'   => 'required',
                'device_code'       => 'required_if:systemid,==,Inventory',
                'shipping_fname'    => 'required',
                'shipping_lname'    => 'required',
                'shipping_mob'      => 'required',
                'shipping_add'      => 'required',
                'shipping_city'     => 'required',
                'shipping_state'    => 'required',
                'shipping_zipcode'  => 'required|min:5|max:5'
        ];
        //);

    }
} 