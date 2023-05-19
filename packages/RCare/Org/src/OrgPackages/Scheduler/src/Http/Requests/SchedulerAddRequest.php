<?php

namespace RCare\Org\OrgPackages\Scheduler\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchedulerAddRequest extends FormRequest 
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
        // return [
        //     'activity_id'       => 'required',
        //     'module_id[]'        => 'required',  
        //     'operation'         => 'required',
        //     'date_of_execution' => 'required',
        //     'time_of_execution' => 'required',
        //     'day_of_execution'  => 'required'
        // ];
        
        $currentdate=date("Y-m-d");
        $year = date("Y",strtotime($currentdate));  
        $month = date("m",strtotime($currentdate));
        $mycurrentdate = $year.'-'.$month.'-01';
        
         $rules = [
            'activity_id'       => 'required',
            'date_of_execution' => 'required|after_or_equal:'.$mycurrentdate,   
            'time_of_execution' => 'required|date_format:H:i:s' 
            
        ];
       
        $b=request()->get('date_of_execution');
        
        if( $b!=null )
        {
            $date = explode("-",$b);  
            $executionday = $date[2];  
            $rules['day_of_execution']='required|gte:'.$executionday; //gte means greater than equal to
            
             
    
        }
       
        
        return $rules;  
    }
}
