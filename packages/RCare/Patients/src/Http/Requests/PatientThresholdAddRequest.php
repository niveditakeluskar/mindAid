<?php
namespace RCare\Patients\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientThresholdAddRequest extends FormRequest
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
      //regex:/^[a-zA-Z0-9_., ]*$/
        return validationRules(True,
            [
               'bpmhigh'         => 'nullable|numeric',
               'bpmlow'          => 'nullable|numeric',
               'glucosehigh'     => 'nullable|numeric',
               'glucoselow'      => 'nullable|numeric',
               'diastolichigh'   => 'nullable|numeric|required_with:systolichigh,diastoliclow',///|required_if:systolichigh,!=,null',
               'diastoliclow'    => 'nullable|numeric|required_with:diastolichigh',//|required_if:diastolichigh',
               'systolichigh'    => 'nullable|numeric|required_with:diastolichigh,systoliclow',//|required_if:diastolichigh,!=,null',
               'systoliclow'     => 'nullable|numeric|required_with:systolichigh',//|required_if:systolichigh',
               'oxsathigh'       => 'nullable|numeric',
               'oxsatlow'        => 'nullable|numeric',
               'temperaturehigh' => 'nullable|numeric',
               'temperaturelow'  => 'nullable|numeric'
            ]
        );
    }
}