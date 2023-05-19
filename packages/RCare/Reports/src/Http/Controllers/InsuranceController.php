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
// use RCare\Reports\Http\Requests\MonthlyBilllingReportPatientsSearchRequest;
use DataTables;
use Carbon\Carbon; 
use Session; 

class InsuranceController extends Controller
{
   
    //created by radha(29dec2020)
    public function getInsuranceReport(Request $request)
    {//dd("hi");
      $insurance = $request->route('insurance');
     // dd($insurance);

      $query1="select concat(p2.fname,' ' ,p2.mname,' ' ,p2.lname) as patient_name ,p2.dob ,p.name as practice,pi2.ins_type ,pi2.ins_provider from patients.patient p2 
join patients.patient_insurance pi2 on p2.id=pi2.patient_id 
join patients.patient_providers pp on p2.id = pp.patient_id 
join ren_core.practices p on pp.practice_id = p.id where pp.provider_type_id = 1 and pi2.ins_provider ilike '%cigna%'
   ";

   $query="select concat(p.fname,' ' , p.mname,' ' , p.lname) as patient_name, p.dob , p2.name as practice, 
pi2.ins_provider as primary_insurance, 
pi3.ins_provider as secondary_provider,
p.status 
from patients.patient p 
inner join patients.patient_providers pp on p.id = pp.patient_id and pp.provider_type_id = 1 and pp.is_active = 1
inner join ren_core.practices p2 on pp.practice_id = p2.id
inner join patients.patient_insurance pi2 on 
p.id = pi2.patient_id  and pi2.ins_type = 'primary'
inner join patients.patient_insurance pi3 
on p.id = pi3.patient_id  and pi3.ins_type = 'secondary'
where  
(pi3.ins_provider ilike '".$insurance."' or pi3.ins_provider ilike '".$insurance."%') or 
(pi2.ins_provider ilike '".$insurance."' or pi2.ins_provider ilike '".$insurance."%')";
     
     $data = DB::select( DB::raw($query) );     
                return Datatables::of($data) 
                ->addIndexColumn()            
                ->make(true);
    }

}


?>