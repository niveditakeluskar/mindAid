<?php
namespace RCare\Ccm\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class CallCloseAddRequest extends FormRequest
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
        $now = Carbon::now();
        $start_of_next_month = $now->addMonth()->startOfMonth('Y-m-d')->format('d-m-Y');

        

        $date = new \DateTime('now');
        $date->modify('last day of this month');
        $lastdayofcurrentmonth = $date->format('d-m-Y') ;
   
        // dd( $lastdayofcurrentmonth );  
        // dd( $start_of_next_month);  
        return validationRules(True,
            [
                // 'health_issue'         => 'required', 
                // 'health_issue_notes'   => 'nullable|required_if:health_issue,1',
                // 'call_next_month'      => 'required',
                // 'next_month_call_date' => 'nullable|required_if:call_next_month,1',
                // 'call_next_month_notes' => 'nullable|required_if:call_next_month,1', 
                'query2'               => 'required|integer',
                // 'q1_notes'             => 'nullable|required_if:q1_notes,not null', 
                'query1'               => 'nullable|required_if:form,ccm',
                // 'q2_notes'             => 'nullable|required_if:query2,1',
                // 'q2_datetime'          => 'nullable|required_if:query2,1|after_or_equal:'.$start_of_next_month, // 'nullable|required_if:query2,1|after:today',
                'q2_datetime'          => 'nullable|required_if:query2,1|after:'.$lastdayofcurrentmonth, 
                
                'q2_time'              => 'nullable|required_if:query2,1',//'required', 
                // 'q2_datetime'        => 'required|after:today',    
                //  'q2_time'           => 'required',
                'q1_notes'             => 'nullable|text_comments_slash', //regex:/^[a-zA-Z0-9 - . , _ \'-]+$/',//regex:/^[a-zA-Z0-9- . , ]*$/', 
                'q2_notes'             => 'nullable|text_comments_slash'    //regex:/^[a-zA-Z0-9- . , ]*$/'    
            ]
        );

    } 
}
