<?php
namespace RCare\Org\OrgPackages\Practices\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class practicesThresholdRequest extends FormRequest
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
        return validationRules(True,
            [
                'bpmhigh'               => 'required|numeric',
                'bpmlow'                => 'required|numeric',
                'diastolichigh'         => 'required|numeric',
                'diastoliclow'          => 'required|numeric', 
                'glucosehigh'           => 'required|numeric',
                'glucoselow'            => 'required|numeric',
                'oxsathigh'             => 'required|numeric',
                'oxsatlow'              => 'required|numeric',
                'systolichigh'          => 'required|numeric',
                'systoliclow'           => 'required|numeric',
                'temperaturehigh'       => 'required|numeric',
                'temperaturelow'        => 'required|numeric'
            ]
        );

    }
}
