<?php
namespace RCare\Reports\Http\Controllers;

use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;
use RCare\TaskManagement\Models\ToDoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RCare\TaskManagement\Models\UserPatients;
use RCare\Org\OrgPackages\Practices\src\Models\Practices;
use RCare\Org\OrgPackages\Providers\src\Models\Providers;
// use RCare\Reports\Http\Requests\MonthlyBilllingReportPatientsSearchRequest;
use DataTables;
use Carbon\Carbon;
use Session;
use Inertia\Inertia;

class ProviderPerformanceController extends Controller
{
  public function providerPerformanceReportList(Request $request)
  {
    return Inertia::render('Report/ManagementReports/ProviderPerformanceReport');
  }

     //created by 27nov2020 drilldown for provider performance report( view file Total no. of patients in ccm)
     public function getPPPatientsDetailsInCCM(Request $request)
     {
      $practiceid = sanitizeVariable($request->route('practiceid'));      
       $providerid = sanitizeVariable($request->route('providerid'));

       $practicename=Practices::where('id',$practiceid)->get('name');
       if($providerid != 'null'){
        $providername=Providers::where('id',$providerid)->get('name');
       }else{
        $providername='';
       }
       

       return Inertia::render('Report/ProviderPerformanceDetailsInCCM', ['practiceId' => $practiceid, 'practiceName' => $practicename, 'providerId' => $providerid, 'providerName' => $providername]);
     }

    //created by 27nov2020 drilldown for provider performance report( view file Total no. of patients in rpm)
     public function getPPPatientsDetailsInRPM(Request $request)
     {
      $practiceid = sanitizeVariable($request->route('practiceid'));      
       $providerid = sanitizeVariable($request->route('providerid'));

       $practicename=Practices::where('id',$practiceid)->get('name');
       if($providerid != 'null'){
        $providername=Providers::where('id',$providerid)->get('name');
       }else{
        $providername='';
       }
       
        return Inertia::render('Report/ProviderPerformanceDetailsInRPM', ['practiceId' => $practiceid, 'practiceName' => $practicename, 'providerId' => $providerid, 'providerName' => $providername]);
     }
 
    //created by 27nov2020 drilldown for provider performance report(Total no. of patients details in ccm)
     public function getPPPatientDetailsInCCMData(Request $request)
     {
      

      $practiceid = sanitizeVariable($request->route('practiceid'));      
       $providerid = sanitizeVariable($request->route('providerid'));

       if($providerid == ""){
        $providerid = 'null';
       }

       $query = "select * from patients.sp_patientdetailsinccmdata($practiceid,$providerid)";
 
            $data = DB::select($query);     
             return Datatables::of($data)
            ->addIndexColumn()   
            ->addColumn('editPatient', function($row){
              
              $btn ='<button class="button" style="border: 0px none;background: none;outline: none;"><a href="/patients/patient-reg-enrollment/'.$row->pid.'" title="Edit Patient Info" data-toggle="tooltip" data-placement="top"  data-original-title="Edit Patient Info" ><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a></button>';
              return $btn;
              // .'/3/19/0
            })
            ->rawColumns(['editPatient'])
            ->make(true);

     }


  //created by 27nov2020 drilldown for provider performance report(Total no. of patients details in rpm)
  public function getPPPatientDetailsInRPMData(Request $request)
  {
    $practiceid = sanitizeVariable($request->route('practiceid'));
    $providerid = sanitizeVariable($request->route('providerid'));
    if ($providerid == "") {
      $providerid = 0;
    }

    $query = "select * from patients.sp_patientdetailsinrpmdata($practiceid,$providerid)";
    $data = DB::select($query);
    return Datatables::of($data)
      ->addIndexColumn()
      ->addColumn('editPatient', function ($row) {

        $btn = '<button class="button" style="border: 0px none;background: none;outline: none;"><a href="/patients/patient-reg-enrollment/'.$row->pid.'" title="Edit Patient Info" data-toggle="tooltip" data-placement="top"  data-original-title="Edit Patient Info" ><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a></button>';
        return $btn;
        // . '/2/18/0
      })
      ->rawColumns(['editPatient'])
      ->make(true);

  }
  //created by radha 20 nov 2020 for provider performance report
  public function getProviderPerformanceReport(Request $request)
  {
    $practicesgrp = $request->route('practicesgrpid');
    $practiceid = sanitizeVariable($request->route('practice'));
    $providerid = sanitizeVariable($request->route('provider'));

    if ($practicesgrp == '' || $practicesgrp == 'null') {
      $practicesgrp = 'null';
    }
    if ($practiceid == '' || $practiceid == 'null') {
      $practiceid = 'null';
    }
    if ($providerid == '' || $providerid == 'null') {
      $providerid = 'null';
    }

    $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus'));

    $query = "select * from patients.SP_Provider_Performance_Report($practiceid,$providerid,$practicesgrp)";
    //  dd($query);  
    $data = DB::select($query);
    return Datatables::of($data)
      ->addIndexColumn()
      ->addColumn('ccmcount', function ($row) {
        if ($row->provider_id == "") {
          $row->provider_id = 0;
        }

        $query = "select * from patients.sp_providerperformance_ccmcount($row->practice_id,$row->provider_id)";
        //  dd($query);
  
        $ccm_count = DB::select($query);
        if (isset($ccm_count[0]->patinetcount)) {
          return $ccm_count[0]->patinetcount;
        } else {
          return 0;
        }

      })
      ->addColumn('rpmcount', function ($row) {
  
        if ($row->provider_id == "") {
          $row->provider_id = 0;
        }

        $query = "select * from patients.sp_providerperformance_rpmcount($row->practice_id,$row->provider_id)";

        $rpm_count = DB::select($query);
        //dd($ccm_count[0]->count);
        if (isset($rpm_count[0]->patinetcount)) {
          return $rpm_count[0]->patinetcount;
        } else {
          return 0;
        }
      })
      ->addColumn('ccmview', function ($row) {
        if ($row->provider_id == "") {
          $row->provider_id = 'null';
        }
        $btn = '<a href="/reports/provider-performance-patient-details-in-ccm/' . $row->practice_id . '/' . $row->provider_id . '" data-toggle="tooltip"  data-original-title="View Details" class="ccm_details" target="_blank" title="View Details"><i class="text-20 i-Eye" style="color: #2cb8ea;"></i></a>';
        return $btn;
      })
      ->addColumn('rpmview', function ($row) {
        if ($row->provider_id == "") {
          $row->provider_id = 'null';
        }
        $btn = '<a href="/reports/provider-performance-patient-details-in-rpm/' . $row->practice_id . '/' . $row->provider_id . '" data-toggle="tooltip" data-id="' . $row->practice_id . '" data-original-title="View Details" class="rpm_details" target="_blank" title="View Details"><i class="text-20 i-Eye" style="color: #2cb8ea;"></i></a>';
        return $btn;
      })
      ->rawColumns(['ccmview', 'rpmview', 'ccmcount', 'rpmcount'])
      ->make(true);


  }

  public function assignedPatients(Request $request)
  {
    $active_pracs = Practices::activePractices();
    $inative_pracs = Practices::InactivePractices();
    return view('Reports::provider-performance-report.provider-performance-report', compact('active_pracs', 'inative_pracs'));
  }

}