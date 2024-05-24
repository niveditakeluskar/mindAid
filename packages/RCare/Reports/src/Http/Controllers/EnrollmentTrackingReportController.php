<?php 
namespace RCare\Reports\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RCare\TaskManagement\Models\UserPatients;
use RCare\Org\OrgPackages\Practices\src\Models\Practices; 
use RCare\Org\OrgPackages\Providers\src\Models\Providers; 
use RCare\System\Traits\DatesTimezoneConversion; 
use DateTime;
// use RCare\Reports\Http\Requests\MonthlyBilllingReportPatientsSearchRequest;
use DataTables;
use Carbon\Carbon; 
use Session;

class EnrollmentTrackingReportController extends Controller
{
    
    public function PatientEnrollReport(Request $request)
    {   
      $monthly = date('Y-m');
      $year = date('Y', strtotime($monthly));
      $month = date('m', strtotime($monthly));
      
      // $diagnosis = "select max(count) from (select uid,count(*) as count from patients.patient_diagnosis_codes where EXTRACT(Month from created_at) = '$month' and EXTRACT(year from created_at) = $year group by uid) x";
      // $diagnosis = DB::select( DB::raw($diagnosis) );    
      //dd($diagnosis);
      return view('Reports::enrollment-tracking-report.enrollment-tracking-report');        
    }


    public function PracticeGroupEnrollmentTrackingList(Request $request)
    {
      // dd($request->all());
      $monthly   = sanitizeVariable($request->route('monthly'));
      if($monthly=='' || $monthly=='null' || $monthly=='0')
      {
          $monthly=date('Y-m'); 
      }
      else
      {
           $monthly=$monthly;
      }
      $year = date('Y', strtotime($monthly));
      $month = date('m', strtotime($monthly));

      $year = date('Y', strtotime($monthly));
      $month = date('m', strtotime($monthly));

      $toyear = date('Y', strtotime($monthly));
      $tomonth = date('m', strtotime($monthly)); 

      $d = cal_days_in_month(CAL_GREGORIAN,$tomonth,$toyear);
     
      
      $fromdate=$year.'-'.$month.'-01';
      $to_date=$toyear.'-'.$tomonth.'-30';    
      $convertdate = strtotime('-1 second', strtotime('+1 month', strtotime($to_date)));
      $todate=date('Y-m-d', $convertdate);
        

      $fdt =$fromdate." "."00:00:00";     
      $tdt = $todate ." "."23:59:59"; 

      $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fdt);
      $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $tdt); 
      
      $monthNum  = $month;
      $dateObj   = DateTime::createFromFormat('!m', $monthNum);
      $monthName = $dateObj->format('F');

        //  $query = "select * from patients.SP_MONTHLY_BILLING_REPORT(null,null,3,timestamp '".$dt1."',timestamp '".$dt2."',null,'".$fromdate."','".$todate."',null,null) sp
        //    left join (select distinct patient_id,count(*)as total,count(quali) as qualified,
        //     count(disquali) as disquali from (select distinct pd.patient_id ,pd.code,r.qualified as quali,dr.qualified as disquali
        //     from patients.patient_diagnosis_codes pd 
        //     left join ren_core.diagnosis_codes r on r.code = pd.code and  r.diagnosis_id  = pd.diagnosis and r.qualified = 1 and r.status =1
        //     left join ren_core.diagnosis_codes dr on dr.code = pd.code and  dr.diagnosis_id  = pd.diagnosis and dr.qualified = 0 and dr.status =1
        //     left join patients.patient_providers pp on pp.patient_id =pd.patient_id
        //     left join ren_core.providers pr on pp.provider_id=pr.id 
        //     left join ren_core.practices prac on pp.practice_id = prac.id
        //     inner join patients.patient_services ps on pd.patient_id=ps.patient_id
        //     where pd.created_at between '".$dt1."'and '".$dt2."' and pd.status =1 and pp.provider_type_id = 1 and pp.is_active =1
     
        //     "; 
        //     $query .=" group by pd.patient_id,pd.code,r.qualified,dr.qualified ) x group  by x.patient_id) y on y.patient_id=sp.pid";

        $query = "select * from ren_core.practicegroup where status = 1 ";

  $data = DB::select($query);
  // $dlength = count($data);
  // $a=array("0","Total");
  // array_push( $data , $a);

  // dd($data);  

  $total_diag=$d;
  $arrydata=array(); 
  $ddata=array(); 
  $columnheader=array();
  $columnheader1=array();
  $finalheader=array();
  $maxcount=0;
  $codedata; 

  for($i=0;$i<count($data);$i++)
  {
      $sum = 0;
      $headername="header".$i;
      $practicegroupid=$data[$i]->id;
      $sumofcurrentmonth_countoflastmonth = array();
   
      if(is_null($data[$i]->practice_name)){
      $practicegroupname ='';
      }else{
        $practicegroupname = $data[$i]->practice_name;
      } 

      $btn = '<a href="javascript:void(0)" class="detailsclick" id="'.$practicegroupid.'"><i data-toggle="tooltip" data-placement="top" class="plus-icons i-Add" data-original-title="View Details" ></i></a>';
               // $btn ='<img src="http://i.imgur.com/SD7Dz.png" id="'.$row->id.'/'.$row->pfromdate.'/'.$row->ptodate.'" >';
                
      $arrydata=array($btn,$practicegroupname);

      for($j=1;$j<=($total_diag);$j++)
      {
        

        $fromdate1=$year.'-'.$month.'-'.$j;
        $todate1=$year.'-'.$month.'-'.$j;   

        // $convertdate = strtotime('-1 second', strtotime('+1 month', strtotime($to_date)));
        // $todate=date('Y-m-d', $convertdate);
        // dd( $todate);  
          
  
        $fdt =$fromdate1." "."00:00:00";     
        $tdt = $todate1 ." "."23:59:59";      
  
        // $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fdt);
        // $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $tdt); 

      
      
        // $practiceid = 1;
        $query1 ="select pp.patient_id,ps.date_enrolled from patients.patient_providers pp
                  inner join patients.patient_services ps on ps.patient_id = pp.patient_id
                  inner join ren_core.practices rp on pp.practice_id = rp.id
                  where rp.practice_group = '".$practicegroupid."' and  ps.date_enrolled  between '".$fdt."'and '".$tdt."' ";

        $countdata = DB::select($query1);   
        $c = count($countdata) ;      
        $sum = $c+$sum; 

       



        // $arrydata[] = $query1;
        $arrydata[] = $c;  
        // $arrydata[] = $sum; 
        // $arrydata[] = $nextmonthcount; 
          //  dd($arrydata) ;  
      }
      
      
      if($month==1){
        $lastmonth = 12;
      }else{
        $lastmonth = $month-1;
      }

      $d = cal_days_in_month(CAL_GREGORIAN,$lastmonth,$year);
      $fromdate1=$year.'-'.$lastmonth.'-01';
      $todate1=$year.'-'.$lastmonth.'-'.$d; 


      $fdt2 =$fromdate1." "."00:00:00";     
      $tdt2 = $todate1 ." "."23:59:59";   
 
      // $practiceid = 1;
      $query2 ="select pp.patient_id,ps.date_enrolled from patients.patient_providers pp
      inner join patients.patient_services ps on ps.patient_id = pp.patient_id
      inner join ren_core.practices rp on pp.practice_id = rp.id
      where rp.practice_group= '".$practicegroupid."' and  ps.date_enrolled  between '".$fdt2."'and '".$tdt2."' ";
      $countdata2 = DB::select($query2); 
     
      $lastmonthcount = count($countdata2);      
      $sumofcurrentmonth_countoflastmonth[0] = $sum;
      $sumofcurrentmonth_countoflastmonth[1] = $lastmonthcount;

      $arrydata = array_merge($arrydata,$sumofcurrentmonth_countoflastmonth);  

      
      
    
   
      $ddata['DATA'][]=$arrydata;   
  }
 

  $dynamicheader=array();
  $columnheader=array("ViewDetails","PracticesGroup");

  for($m=0;$m<count($columnheader);$m++)
  { 
     $dynamicheader[]=array("title"=>$columnheader[$m]);
  }

  $add = $total_diag; 
  for($k=1;$k<=$add;$k++)
  { 
      $varheader=$monthName.$k;
      $dynamicheader[]=array("title"=>$varheader);
  }

  $columnheader1=array("TotalMonth","Total Last Month");
  for($m1=0;$m1<count($columnheader1);$m1++)
  {
      $dynamicheader[]=array("title"=>$columnheader1[$m1]);
  }

  $fdata['COLUMNS']=$dynamicheader;
    
  $finldata=array_merge($fdata,$ddata);
  // dd($finldata);

  return json_encode($finldata);




 }

    public function PatientEnrollmentTrackingList(Request $request) 
    {

      $new = array();
      // dd($request->all());
      $monthly   = sanitizeVariable($request->route('monthly'));
      if($monthly=='' || $monthly=='null' || $monthly=='0')
      {
          $monthly=date('Y-m'); 
      }
      else
      {
           $monthly=$monthly;
      }
      $year = date('Y', strtotime($monthly));
      $month = date('m', strtotime($monthly));

      $year = date('Y', strtotime($monthly));
      $month = date('m', strtotime($monthly));

      $toyear = date('Y', strtotime($monthly));
      $tomonth = date('m', strtotime($monthly)); 

      $d = cal_days_in_month(CAL_GREGORIAN,$tomonth,$toyear);
     
      
      $fromdate=$year.'-'.$month.'-01';
      $to_date=$toyear.'-'.$tomonth.'-30';    
      $convertdate = strtotime('-1 second', strtotime('+1 month', strtotime($to_date)));
      $todate=date('Y-m-d', $convertdate);
        

      $fdt =$fromdate." "."00:00:00";     
      $tdt = $todate ." "."23:59:59"; 

      $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fdt);
      $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $tdt); 
      
      $monthNum  = $month;
      $dateObj   = DateTime::createFromFormat('!m', $monthNum);
      $monthName = $dateObj->format('F');

      
  $query1 = "select * from ren_core.practicegroup where practice_name='HMG'";

       
  $data1 = DB::select($query1);

  // dd();

  $query2 = "select pr.*,pg.practice_name as practicegroup from ren_core.practices pr
  inner join ren_core.practicegroup pg on pr.practice_group = pg.id
  where is_active = 1 and practice_group = 7";
  $data2 = DB::select($query2);
  // dd($data2);

  $a = array_merge($data1,$data2); 
  // var_dump($a);
  // dd();

  $other =  [
          "id"=> 0,
          "practice_name"=> "Other",
          "status"=> 1,
          "created_by"=> 1,
          "updated_by"=> 1,
          "created_at"=> "2020-12-04 05:59:37",
          "updated_at"=>"2021-03-24 16:01:08",
          "assign_message"=> 1
  ];

  $objectother = (object) $other;
 
  // $otherjsondata = json_encode($other);
  $otherarray = array();
  $otherarray[0] = $objectother;
      
// dd($data1,$b,$f,$try);
   
  $c = array_merge($a,$otherarray);
  // dd($c);



  $query3 = "select  pr.*,pg.practice_name as practicegroup from ren_core.practices pr
             inner join ren_core.practicegroup pg on pr.practice_group = pg.id
             where pr.is_active = 1 and pr.practice_group != 7 ";  
  $data3 = DB::select($query3);




  $datawithouttotal = array_merge($c,$data3);


  $total =  [
    "id"=> null,
    "practice_name"=> "Total",
    "status"=> 1,
    "created_by"=> 1,
    "updated_by"=> 1,
    "created_at"=> "2020-12-04 05:59:37",
    "updated_at"=>"2021-03-24 16:01:08",
    "assign_message"=> 1
    ];
    
    $objecttotal = (object) $total;
    $totalarray = array();
    $totalarray[0] = $objecttotal;
    // $data = array_merge($datawithouttotal,$totalarray);
    $data = array_merge($data2,$data3);
    // dd($data);  






  //  dd($data);    

  $total_diag=$d;
  $arrydata=array(); 
  $ddata=array(); 
  $columnheader=array();
  $columnheader1=array();
  $finalheader=array();
  $maxcount=0;
  $codedata; 
//  dd($data);  

  for($i=0;$i<count($data);$i++)
  {
 
 
    // dd($data[$i]);  
    $sum = 0;
    $headername="header".$i;
    

    if (property_exists($data[$i], "practice_name")) {
      //dd($data[$i]);  
      $practicegroupid=$data[$i]->id;
      $name = $data[$i]->practice_name; 
    }else{
     $practiceid=$data[$i]->id;
     $name = $data[$i]->name;
     $practicegroupname=  $data[$i]->practicegroup;  
    //  $mypracticegroupid = $data[$i]->practice_group;
    //  dd( $practicegroupname);
    }


      
     
      $sumofcurrentmonth_countoflastmonth = array();
   
      // if(is_null($data[$i]->name)){
      // $practicename ='';
      // }else{
      //   $practicename = $data[$i]->name;
      // } 


      $arrydata=array($name,$practicegroupname);
      // dd($arrydata);

      for($j=1;$j<=($total_diag);$j++)
      {
        

        $fromdate1=$year.'-'.$month.'-'.$j;
        $todate1=$year.'-'.$month.'-'.$j;   

        // $convertdate = strtotime('-1 second', strtotime('+1 month', strtotime($to_date)));
        // $todate=date('Y-m-d', $convertdate);
        // dd( $todate);  
          
  
        $fdt =$fromdate1." "."00:00:00";     
        $tdt = $todate1 ." "."23:59:59";      
  
        // $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fdt);
        // $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $tdt); 

      
      
        // $practiceid = 1;
        if (property_exists($data[$i], "practice_name")) {
            if($practicegroupid == 0){
              $query1 ="select pp.patient_id,ps.date_enrolled from patients.patient_providers pp
              inner join patients.patient_services ps on ps.patient_id = pp.patient_id
              inner join ren_core.practices rp on pp.practice_id = rp.id
              where rp.practice_group != 7 and  ps.date_enrolled  between '".$fdt."'and '".$tdt."' ";
            }else if($practicegroupid == null){
              $query1 ="select pp.patient_id,ps.date_enrolled from patients.patient_providers pp
              inner join patients.patient_services ps on ps.patient_id = pp.patient_id
              inner join ren_core.practices rp on pp.practice_id = rp.id
              where ps.date_enrolled  between '".$fdt."'and '".$tdt."' ";
            }
            else{
              $query1 ="select pp.patient_id,ps.date_enrolled from patients.patient_providers pp
              inner join patients.patient_services ps on ps.patient_id = pp.patient_id
              inner join ren_core.practices rp on pp.practice_id = rp.id
              where rp.practice_group = '".$practicegroupid."' and  ps.date_enrolled  between '".$fdt."'and '".$tdt."' ";
            }
          

        }else{
          $query1 ="select pp.patient_id,ps.date_enrolled from patients.patient_providers pp
          inner join patients.patient_services ps on ps.patient_id = pp.patient_id
          where pp.practice_id = '".$practiceid."' and  ps.date_enrolled  between '".$fdt."'and '".$tdt."' ";
        }  
        

        $countdata = DB::select($query1);   
        $c = count($countdata) ;      
        $sum = $c+$sum; 

       



        // $arrydata[] = $query1;
        $arrydata[] = $c;  
        // $arrydata[] = $sum; 
        // $arrydata[] = $nextmonthcount; 
          //  dd($arrydata) ;  
      }
      
      
      if($month==1){
        $lastmonth = 12;
      }else{
        $lastmonth = $month-1;
      }

      $d = cal_days_in_month(CAL_GREGORIAN,$lastmonth,$year);
      $fromdate1=$year.'-'.$lastmonth.'-01';
      $todate1=$year.'-'.$lastmonth.'-'.$d; 


      $fdt2 =$fromdate1." "."00:00:00";     
      $tdt2 = $todate1 ." "."23:59:59";   
 
      // $practiceid = 1;
      if (property_exists($data[$i], "practice_name")) {
        if($practicegroupid == 0){
          $query2 ="select pp.patient_id,ps.date_enrolled from patients.patient_providers pp
          inner join patients.patient_services ps on ps.patient_id = pp.patient_id
          inner join ren_core.practices rp on pp.practice_id = rp.id
          where rp.practice_group != 7 and  ps.date_enrolled  between '".$fdt."'and '".$tdt."' ";
        }else if($practicegroupid == null){
          $query2 ="select pp.patient_id,ps.date_enrolled from patients.patient_providers pp
          inner join patients.patient_services ps on ps.patient_id = pp.patient_id
          inner join ren_core.practices rp on pp.practice_id = rp.id
          where ps.date_enrolled  between '".$fdt."'and '".$tdt."' ";  
        }
        else{
          $query2 ="select pp.patient_id,ps.date_enrolled from patients.patient_providers pp
          inner join patients.patient_services ps on ps.patient_id = pp.patient_id
          inner join ren_core.practices rp on pp.practice_id = rp.id
          where rp.practice_group= '".$practicegroupid."' and  ps.date_enrolled  between '".$fdt2."'and '".$tdt2."' ";
        }
       
      }else{
        $query2 ="select pp.patient_id,ps.date_enrolled from patients.patient_providers pp
        inner join patients.patient_services ps on ps.patient_id = pp.patient_id
        where pp.practice_id = '".$practiceid."' and  ps.date_enrolled  between '".$fdt2."'and '".$tdt2."' ";
      }
 

      $countdata2 = DB::select($query2); 
     
      $lastmonthcount = count($countdata2);      
      $sumofcurrentmonth_countoflastmonth[0] = $sum;
      $sumofcurrentmonth_countoflastmonth[1] = $lastmonthcount;

      $arrydata = array_merge($arrydata,$sumofcurrentmonth_countoflastmonth);  
// dd( $arrydata);
      
      
    
   
      $ddata['DATA'][]=$arrydata;   
  }
 

  $dynamicheader=array();

  $columnheader=array("Practices",config('global.practice_group') );

  for($m=0;$m<count($columnheader);$m++)
  { 
     $dynamicheader[]=array("title"=>$columnheader[$m]);
  }

  $add = $total_diag; 
  // $add = 3; 
  for($k=1;$k<=$add;$k++)
  { 
      $varheader=$monthName." ".$k;
      $dynamicheader[]=array("title"=>$varheader);
  }

  $columnheader1=array("TotalMonth","Total Last Month");
  for($m1=0;$m1<count($columnheader1);$m1++)
  {
      $dynamicheader[]=array("title"=>$columnheader1[$m1]);
  }

  $fdata['COLUMNS']=$dynamicheader;
    
  $finldata=array_merge($fdata,$ddata);
  // dd($finldata);
 

  return json_encode($finldata);  

  // return $php_array; 




 }



    
}

     