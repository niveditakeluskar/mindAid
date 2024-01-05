<?php

namespace RCare\Org\OrgPackages\Scheduler\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportSchedulerAddRequest extends FormRequest 
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
    $currentdate=date("Y-m-d");
    $year = date("Y",strtotime($currentdate));  
    $month = date("m",strtotime($currentdate));
    $mycurrentdate = $year.'-'.$month.'-01'; //"2022-07-01"
    
    
     $rules = [
        'report_id'                => 'required',
        //'user_id'                  => 'required',
        'report_format'            =>'required',
        'date_of_execution'        => 'required|after_or_equal:'.$mycurrentdate,   
        'report_time_of_execution' => 'required|date_format:H:i:s',
        //'frequency'                =>'required'
        
    ]; 
   
    $b=request()->get('date_of_execution');
    //dd($b);
    // if( $b!=null )
    // {
    //     $date = explode("-",$b);  
    //     $executionday = $date[2];  
    //     $rules['day_of_execution']='required|gte:'.$executionday; //gte means greater than equal to
        
         

    // }
   
    
    return $rules;  
}    
}
