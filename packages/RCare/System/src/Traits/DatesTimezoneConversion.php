<?php
namespace RCare\System\Traits;
use DateTimeInterface;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Illuminate\Http\Request;
use Session;

trait DatesTimezoneConversion
{
	/*
	public function getCreatedAtAttribute($value)
    {
        $timezone = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
		//dd($timezone);
		//print_r( Session::get('timezone'));
		//echo $value;
        if ($value instanceof Carbon) {
            return $value->timezone($timezone);
		   
        }
		
		if ($value instanceof DateTimeInterface) {
            return new Carbon(
                $value->format('Y-m-d H:i:s.u'), $timezone
            );
			
        }

        if (is_numeric($value)) {
            return Carbon::createFromTimestamp($value)->timezone($timezone);
			
        }

        if ($this->isStandardDateFormat($value)) {
            return Carbon::createFromFormat('Y-m-d', $value)->startOfDay()->timezone($timezone);
			
        }		
		
        return Carbon::createFromFormat(
            str_replace('.v', '.u', $this->getDateFormat()), $value
        )->timezone($timezone);
		
    }
	
	
	// q2_time
	public function setServiceEndDateAttribute($value)
	{
		 echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		$this->attributes['service_end_date'] = $this->userToConfigTimeStamp($value);		
	}
	
	public function getServiceEndDateAttribute($value)
    {		
		return $this->userTimeStamp($value);		
	}
	// end q2_time
	*/


    //for date_of_execution 
	// public function setDateofExecutionAttribute($value)
	// {
				
	// 	$this->attributes['date_of_execution'] = $this->userToConfigTimeStamp($value);		
	// }
	
	// public function getDateofExecutionAttribute($value)
    // {		
		
	// 	return $this->userTimeStampDate($value);		
	// }

	// //for time_of_execution
	// public function setTimeofExecutionAttribute($value)  
	// {
				
	// 	$this->attributes['time_of_execution'] = $this->userToConfigTimeStamp($value);		
	// }
	
	// public function getTimeofExecutionAttribute($value)
    // {		
		
	// 	return $this->userTimeStampDate($value);		
	// }

	//start for scheduler_date
	public function setSchedulerdateofExecutionAttribute($value)   
	{  
		//echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);			
		$this->attributes['scheduler_date'] = $this->userToConfigTimeStamp($value);
		
		
	}

	public function getSchedulerdateofExecutionAttribute($value)
    {		
		
		return $this->userTimeStampDate($value); 		
	}

	// ====================================================

	
	public function getSchedulerDateAttribute($value)
    {		
		
		return $this->userTimeStamp($value); 		
	}

	public function setSchedulerDateAttribute($value)   
	{  
		
		//echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);			
		$this->attributes['scheduler_date'] = $this->userToConfigTimeStamp($value);
		 
		  
	}
	
	//end for scheduler_date

	
	

	//for review_date of diagnosiscode
	public function setReviewDateAttribute($value)   
	{  
		//echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);	
		
		if(strlen($value)>11){
			$this->attributes['review_date'] = $this->userToConfigTimeStamp($value);
		}else{
			return $this->attributes['review_date'] = $value;
		}
		   	
	}
	//for review_date of diagnosiscode
	public function getReviewDateAttribute($value)  
    {		
		
		return $this->userTimeStampYmd($value); 		
	}

	//for update_date of diagnosiscode
	public function setUpdateDateAttribute($value)   
	{  
		//echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);	
		if(strlen($value)>11){
		$this->attributes['update_date'] = $this->userToConfigTimeStamp($value);
		}else{
			return $this->attributes['update_date'] =$value;
		}
		   	
	}  
	//for update_date of diagnosiscode
	public function getUpdateDateAttribute($value)
    {				
		return $this->userTimeStampYmd($value); 		
	}  




	
	//for active and deactive  
      public function setActiveDeactiveFromDateAttribute($value)
	{
		// echo "service end date".$value;
		 //echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);
		 		
		$this->attributes['activedeactivefromdate'] = $this->userToConfigTimeStamp($value);		
	}
	
	public function getActiveDeactiveFromDateAttribute($value)
    {		
		
		return $this->userTimeStampDate($value);		
	}


   //for active and deactive  to date 
      public function setActiveDeactiveToDateAttribute($value)
	{
		// echo "service end date".$value;
		 //echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		$this->attributes['activedeactivetodate'] = $this->userToConfigTimeStamp($value);		
	}
	
	public function getActiveDeactiveToDateAttribute($value)
    {		
		
		return $this->userTimeStampDate($value);		
	}
	
	//service_end_date	
     public function setServiceEndDateAttribute($value)
	{
		//echo "service end date".$value;
		 //echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		$this->attributes['service_end_date'] = $this->userToConfigTimeStamp($value);//->configTimeStamp($value);		
	}
	
	public function getServiceEndDateAttribute($value)
    {		
		// return $this->userTimeStampDate($value);	
		return $this->userTimeStampDatePicker($value);	
	}
	// service_start_date
	
	//service_start_date
      public function setServiceStartDateAttribute($value)
	{
		 //echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		$this->attributes['service_start_date'] = $this->userToConfigTimeStamp($value);//->configTimeStamp($value);		
	}
	
	public function getServiceStartDateAttribute($value)
    {		
		// return $this->userTimeStampDate($value);	
		return $this->userTimeStampDatePicker($value);
	}
	// service_start_date
	
	
	//last_visit_date
      public function setLastVisitDateAttribute($value)
	{
		 //echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		$this->attributes['last_visit_date'] = $this->userToConfigTimeStamp($value);		
	}
	
	public function getLastVisitDateAttribute($value)
    {		
		return $this->userTimeStampDate($value);		
	}

	public function setimagingDateAttribute($value)
	{
		// echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		$this->attributes['imaging_date'] = $this->userToConfigTimeStamp($value);	
		// $this->attributes['imaging_date'] = $this->configTimeStamp($value);	
	}
	
	public function getimagingDateAttribute($value)
    {		
		return $this->userTimeStamp($value);		
	}

	public function setHealthDateAttribute($value)  
	{
		// echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		// $this->attributes['health_date'] = $this->userToConfigTimeStamp($value);	
		$this->attributes['health_date'] = $this->configTimeStamp($value);      		
	}
	 
	public function geHealthDateAttribute($value)
    {		
		return $this->userTimeStamp($value);		
	}

	public function setLabDateAttribute($value)
	{
		// echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		// $this->attributes['health_date'] = $this->userToConfigTimeStamp($value);	
		$this->attributes['lab_date'] = $this->configTimeStamp($value);      		
	}
	 
	public function geLabDateAttribute($value)
    {		
		return $this->userTimeStamp($value);		
	}     

	public function setDateEnrolledAttribute($value)
	{
		// echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		$this->attributes['date_enrolled'] = $this->configTimeStamp($value);		
	}
	 
	public function getDateEnrolledAttribute($value)
    {		
		return $this->userTimeStamp($value);		
	}
	//date_enrolled
     /*public function setDateEnrolledAttribute($value)
	{
		 echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		$this->attributes['date_enrolled'] = $this->userToConfigTimeStamp($value);		
	}*/
	
	// public function getDateEnrolledAttribute($value)
    //   {		
	// 	return $this->userTimeStamp($value);		
	// }

	//record_date
	public function setRecordDateAttribute($value)
	{
		 //echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		// $this->attributes['record_date'] = $this->userToConfigTimeStamp($value);	
		$this->attributes['record_date'] = $this->configTimeStamp($value);	 
	}
	
	public function getRecordDateAttribute($value)
    {		
		return $this->userTimeStamp($value);		
	}	
	// rec_date

	// finalize_date
	public function setFinalizeDateAttribute($value)
	{
		 //echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		// $this->attributes['record_date'] = $this->userToConfigTimeStamp($value);	
		$this->attributes['finalize_date'] = $this->configTimeStamp($value);	 
	}
	
	public function getFinalizeDateAttribute($value)
    {		
		return $this->userTimeStamp($value);		
	}
	//finalize_date

	// message_date
	public function setMessageDateAttribute($value)
	{
		 //echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		// $this->attributes['record_date'] = $this->userToConfigTimeStamp($value);	
		$this->attributes['message_date'] = $this->configTimeStamp($value);	 
	}
	
	public function getMessageDateAttribute($value)
    {		
		return $this->userTimeStamp($value);		
	}
	//message_date

	public function setRecDateAttribute($value)
	{
		 //echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		$this->attributes['rec_date'] = $this->configTimeStamp($value);		
	}
	
	public function getRecDateAttribute($value)
    {		
		return $this->userTimeStamp($value);		
	}
	
	
	public function setFollowUpDateAttribute($value)
	{
		//echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);	
		//echo "saving followup date".$value;
		$this->attributes['follow_up_date'] = $this->userToConfigTimeStamp($value);		
	}
	
	public function getFollowUpDateAttribute($value)
    {		
		//echo "followup date".$value;
		return $this->userTimeStampDate($value);		
	}
	// end q2_time
	
	
	
	
	
	// ccm_call_followup_date
	public function setCcmCallFollowupDateAttribute($value)
	{
		// echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		$this->attributes['ccm_call_followup_date'] = $this->userToConfigTimeStamp($value);		
	}
	
	public function getCcmCallFollowupDateAttribute($value)
    {		
		return $this->userTimeStamp($value);		
	}
	// end ccm_call_followup_date
	
	// ccm_answer_followup_date
	public function setCcmAnswerFollowupDateAttribute($value)
	{
		// echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		$this->attributes['ccm_answer_followup_date'] = $this->userToConfigTimeStamp($value);		
	}
	
	public function getCcmAnswerFollowupDateAttribute($value)
    {		
		return $this->userTimeStamp($value);		
	}
	// end ccm_answer_followup_date
	
	
	//task_date
	public function setTaskDateAttribute($value)
	{
		// echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		$this->attributes['task_date'] = $this->userToConfigTimeStamp($value);		
	}
	
	public function getTaskDateAttribute($value)
    {		
		return $this->userTimeStamp($value);		
	}
	// end task_date
	
	// callback_date
	public function setCallbackDateAttribute($value)
	{
		// echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		$this->attributes['callback_date'] = $this->userToConfigTimeStamp($value);		
	}
	
	public function getCallbackDateAttribute($value)
    {		
		return $this->userTimeStamp($value);		
	}
	// end callback_date
	
	// q2_time
	public function setQ2DatetimeAttribute($value)
	{
		// echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		$this->attributes['q2_datetime'] = $this->userToConfigTimeStamp($value);		
	}
	
	public function getQ2DatetimeAttribute($value)
    {		
		return $this->userTimeStampDate($value);		
	}
	// end q2_time


	// eff_date
	public function seteffAttribute($value)
	{
		// echo  $value. " converted UTC ".$this->userToConfigTimeStamp($value);		
		$this->attributes['eff_date'] = $this->userToConfigTimeStamp($value);		
	}
	
	public function geteffAttribute($value)
    {		
		return $this->userTimeStampDate($value);		
	}
	// end eff_date
	
	
	public function setUpdatedAtAttribute($value)
	{
		$this->attributes['updated_at'] = $this->configTimeStamp($value);		
	}
	
	public function getUpdatedAtAttribute($value)
    {		
		
		return $this->userTimeStamp($value);		
	}

    public function setCreatedAtAttribute($value)
	{
		//echo $value;
		
		$this->attributes['created_at'] = $this->configTimeStamp($value);
	}

	public function getLoginTimeAttribute($value)
    {		
		
		return $this->userTimeStamp($value);		
	}

    public function setLoginTimeAttribute($value)
	{
		//echo $value;
		
		$this->attributes['login_time'] = $this->configTimeStamp($value);
	}

	public function getLogoutTimeAttribute($value)
    {		
		
		return $this->userTimeStamp($value);  		
	}

    public function setLogoutTimeAttribute($value)  
	{
		//echo $value;
		
		$this->attributes['logout_time'] = $this->configTimeStamp($value);
	}

	
	
	public function getCreatedAtAttribute($value)
    {		
		return $this->userTimeStamp($value);		
	}
	
	public static function userToConfigTimeStamp($value){	
		// dd($value);  
		if($value!="" && $value!=null){
			// echo "value".$value;  
			// print_r(date('Y-m-d H:i:s',strtotime($value)));
			// $value = date('Y-m-d H:i:s', strtotime($value));
			// $value = strtotime($value);
			// dd($value);
			// $value = Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('Y-m-d H:i:s');
			// dd($value) ;
			// $value = \DateTime::createFromFormat('Y-m-d H:i:s', $value)->format('Y-m-d H:i:s');
			// dd($value);

			// if(strtotime($value)){
			// 	$value = date('Y-m-d H:i:s', strtotime($value));
			// }
			// else{
				// $value = date('Y-m-d H:i:s', $value);
			// }  

			// $value = new \DateTime($value);
			// $value1 = $value->format('Y-m-d H:i:s');
			// dd($value);
			// dd($date1);  
			// dd($value, $date1);
            // $dt1 = $value1->setTimezone(new \DateTimeZone('UTC'));
			// $date1 = \DateTime::createFromFormat('Y-m-d H:i:s', $value1, new \DateTimeZone('UTC'));
			// dd($date1);   
			// $abc1 = Carbon::createFromFormat('Y-m-d H:i:s', $date1, $timezone)->setTimezone(config('app.timezone'));

			// dd($abc1);

			/******** ashvini arumugam ***/
			//  $value = new \DateTime($value);
			//  $value1 = $value->format('Y-m-d H:i:s');
			//  $date1 = \DateTime::createFromFormat('Y-m-d H:i:s', $value1, new \DateTimeZone('UTC'));
			//  return $date1->toDateTimeString();   
			//  dd($date1);

			//  $server_tz = date_default_timezone_get();
			//  dd($server_tz );
			
			$configTZ     = config('app.timezone');
			$userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone_US'); 		
		    // $tz_from 	  = 'Asia/Calcutta'; //$userTZ not working
			$tz_from      = $userTZ;
            $tz_to        = $configTZ;
        	$format       = 'Ymd\THis\Z';			
            $dt11 = new \DateTime($value , new \DateTimeZone($tz_from));			
            $dt11->setTimeZone(new \DateTimeZone($tz_to));
            $dt1 =  $dt11->format('Y-m-d H:i:s');
			
			return $dt1; 	
			
			// dd($value, $dt1, $tz_from, $tz_to);    
       


			// ************pranali mam ***
			
			//$value = date('Y-m-d H:i:s', strtotime($value));		
			//$timezone = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
			// $timezone = 'Asia/Calcutta';	
			//$abc = Carbon::createFromFormat('Y-m-d H:i:s', $value, $timezone)->setTimezone(config('app.timezone'));		
			// return $abc->toDateTimeString();
			//dd($value,$abc, Session::get('timezone') );
			/************pranali mam */


		}else{
			return $value;
		}
	}
	
	
	public static function configTimeStamp($value){	
		$value = date('Y-m-d H:i:s', strtotime($value));
		$abc = Carbon::createFromFormat('Y-m-d H:i:s', $value, config('app.timezone'))->setTimezone(config('app.timezone'));
		// echo $value;
		// echo  $abc->toDateTimeString(); 
		return $abc->toDateTimeString();
	}
	
	
	
	public static function userTimeStamp($value){
		/*$temp = explode('.', $value);
		if(count($temp) == 2) {
                unset($temp[count($temp) - 1]); // remove the millisecond part
            } else {
                $temp = $value; // created_at didnt have milliseconds set it back to original
            }
			
		$value =	Carbon::parse(implode('.', $temp))->format('Y-m-d H:i:s');
		//*/
		
		if($value!="" && $value!= null){
			//echo $value.'check val';
			$value = date('Y-m-d H:i:s', strtotime($value));
			$timezone = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
			//echo $timezone;
			$abc = Carbon::createFromFormat('Y-m-d H:i:s', $value, config('app.timezone'))->setTimezone(Session::get('timezone'));	
			//echo Session::get('timezone');
			//echo 'time Zone <br>';
			//echo Carbon::parse($abc)->format('m-d-Y H:i:s');
			return Carbon::parse($abc)->format('m-d-Y H:i:s');
		}else{
			return "";
			
		}
		
	}
	
	public static function userTimeStampYmd($value){
		/*$temp = explode('.', $value);
		if(count($temp) == 2) {
                unset($temp[count($temp) - 1]); // remove the millisecond part
            } else {
                $temp = $value; // created_at didnt have milliseconds set it back to original
            }
			
		$value =	Carbon::parse(implode('.', $temp))->format('Y-m-d H:i:s');
		//*/
		
		if($value!="" && $value!= null){
			//echo $value.'check val';
			$value = date('Y-m-d H:i:s', strtotime($value));
			$timezone = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
			//echo $timezone;
			$abc = Carbon::createFromFormat('Y-m-d H:i:s', $value, config('app.timezone'))->setTimezone(Session::get('timezone'));	
			//echo Session::get('timezone');
			//echo 'time Zone <br>';
			//echo Carbon::parse($abc)->format('m-d-Y H:i:s');
			return $abc->toDateTimeString();
		
		}else{
			return "";
			
		}
		
	}

	public static function userNewTimeStamp($value){ //created and modified by ashvini 17th july 2021 for Y-m-d H:i:s format and toDateTimeString
		/*$temp = explode('.', $value);
		if(count($temp) == 2) {
                unset($temp[count($temp) - 1]); // remove the millisecond part
            } else {
                $temp = $value; // created_at didnt have milliseconds set it back to original
            }
			
		$value =	Carbon::parse(implode('.', $temp))->format('Y-m-d H:i:s');
		//*/
					
		if($value!="" && $value!= null){
			$value = date('Y-m-d H:i:s', strtotime($value));
			$timezone = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
			//echo $timezone;
			$abc = Carbon::createFromFormat('Y-m-d H:i:s', $value, config('app.timezone'))->setTimezone(Session::get('timezone'));	
			//echo Session::get('timezone');
			//echo Carbon::parse($abc)->format('m-d-Y H:i:s');
			// return Carbon::parse($abc)->format('Y-m-d H:i:s'); 
			return $abc->toDateTimeString();
		}else{
			return "";
			
		}
		
	}
	
	private function userTimeStampDate($value){
		//dd($value);
		/*$temp = explode('.', $value);
		if(count($temp) == 2) {
                unset($temp[count($temp) - 1]); // remove the millisecond part
            } else {
                $temp = $value; // created_at didnt have milliseconds set it back to original
            }
			
		$value =	Carbon::parse(implode('.', $temp))->format('Y-m-d H:i:s');
		//*/
		
		if($value!="" && $value!=null){
			$value = date('Y-m-d H:i:s', strtotime($value));
			$timezone = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
			$abc = Carbon::createFromFormat('Y-m-d H:i:s', $value, config('app.timezone'))->setTimezone(Session::get('timezone'));	
			
			//return Carbon::parse($abc)->format('Y-m-d');
			return Carbon::parse($abc)->format('m-d-Y');
		}else{
			return "";
			
		}
		
	}
   
	private function userTimeStampDatePicker($value){
		if($value!="" && $value!=null){
			$value = date('Y-m-d H:i:s', strtotime($value));
			$timezone = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
			$abc = Carbon::createFromFormat('Y-m-d H:i:s', $value, config('app.timezone'))->setTimezone(Session::get('timezone'));	
			
			 return Carbon::parse($abc)->format('m-d-Y');
		}else{
			return "";
			
		}

	}   
	
	
	// Only Time Functions
	private function configTime($value){
		//echo $value;
		if($value){
		$abc = Carbon::createFromFormat('H:i:s.u', $value, config('app.timezone'))->setTimezone(config('app.timezone'));
		return $abc->toDateTimeString();
		}else{
			return $value;
		}
	}
	
	private function userTime($value){
		//$value = date('Y-m-d H:i:s', strtotime($value));
		if($value !="" && $value!=null){
			$timezone = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
			$abc = Carbon::createFromFormat('H:i:s.u', $value, config('app.timezone'))->setTimezone(Session::get('timezone'));		
			return $abc->toDateTimeString();
		}else{
			return $value;
		}
	}
	// End of Only Time Functions
    
}
