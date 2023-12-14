<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;
use RCare\Patients\Models\Patients;

class RpmBulkuploadDevices implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {     
        foreach($collection as $row) {
        $em1=trim($row[0]);          
        // $em = collect([$em1]);
        $partner_get = strtolower(trim($row[0]));
        $practice_get = strtolower(trim($row[1]));
        $fname_get = strtolower(trim($row[2]));
        $lname_get = strtolower(trim($row[3]));
        $dob_get = strtolower(trim($row[4]));
        $device_code_get = strtolower(trim($row[5])); 
        $device_get = strtolower(trim($row[6]));


        $partner = collect([$partner_get]);
        $practice = collect([$practice_get]);
        $fname = collect([$fname_get]);
        $lname = collect([$lname_get]);
        $dob_dateString = collect([$dob_get]);
        
        // $dob = collect([$dob_get]);
        $device_code = collect([$device_code_get]); 
        $device = collect([$device_get]);
        $expectedFormat = "Y-m-d";
    
            if (isset($dob_dateString)) {
                $newDate = date("Y-m-d", strtotime($dob_dateString));  
                echo "New date format is: ".$newDate. " (YYYY-MM-DD)";   
            //     echo $dob_dateString .'jjjjj';
              
            //    $date = $dob_dateString->format("Y-m-d"); 

            //    // $date = $dateString->format($expectedFormat);
            //     $dob = $date;
            //     //return 'Invalid date format for'  .' '.  $fname .' '. $lname;
            // } else {
            //     $date = DateTime::createFromFormat($expectedFormat, $dateString);
            //     if ($date === false) {
            //         // return 'Invalid date for'  .' '.  $fname .' '. $lname;
            //         $dob = $date->format('Y-m-d'); 
            //     } else {
            //         // echo 'Date format is valid';
            //         $dob = $excelData[$i]['dob'];
            //     }
            }

        if($partner->filter()->isNotEmpty()){

            // you logic can go here
            print_r($fname);echo"<pre>";
            print_r($lname);echo"<pre>"; 
            print_r($dob_dateString);echo"<pre>"; 
              $data = "select distinct(p.id) from patients.patient p
              where LOWER(p.fname) like '%".$fname."%' ";
            //   and LOWER(p.lname) like '%".$lname."%'
            //   and p.dob = TO_DATE('".$dob."', 'YYYY-MM-DD')";
                $data_exist_query = DB::select($data);
            
                if($data_exist_query!=''){
                    // print_r($data_exist_query);
                    echo "Yes";
                }else{
                    echo "Nothing";
                } 

            // $user = Content::create([
            // 'partner' => $collection[0],
            //  'practice'     => $collection[1],
            //  'fname'    => $collection[2],
            //  'lname' => $collection[3],
            // 'dob'     => $collection[4],
            // 'device_code'    => $collection[5], 
            // 'device'    => $collection[6],
       
            // ]);
            //   print_r($partner ."==" .$practice."==".$fname."==" .$lname."==".$dob."==" .$device_code."==".$device)."<br>";                
               
        }
        // you logic can go here
        // Define how to create a model from the Excel row data
        // return new RpmBulkuploadDevices([ 
        //    'partner' => $collection[0],
        //    'practice'     => $collection[1],
        //    'fname'    => $collection[2],
        //    'lname' => $collection[3],
        //    'dob'     => $collection[4],
        //    'device_code'    => $collection[5], 
        //    'device'    => $collection[6],
        //     // Add more columns as needed 
        // ]);
        }
        
        die;
    } 
}
 