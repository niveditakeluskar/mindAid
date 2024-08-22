<?php
namespace RCare\Org\OrgPackages\Medication\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Medication\src\Http\Requests\MedicationRequest;
use RCare\Org\OrgPackages\Medication\src\Http\Requests\CategoryRequest;
use RCare\Org\OrgPackages\Medication\src\Http\Requests\SubCategoryRequest;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\Medication\src\Models\Medication;
use RCare\Org\OrgPackages\Medication\src\Models\Category;
use RCare\Org\OrgPackages\Medication\src\Models\SubCategory;
//use RCare\Org\OrgPackages\Medication\src\Models\Providers;
use DataTables;
use Hash;
use Redirect,Response;
use Session;
use File,DB;
use Illuminate\Validation\Rule;
use Validator;

class MedicationController extends Controller {
    // public function index1() {
    //     return view('Medication::medications_list');
    // }

    public function addMedication(MedicationRequest $request) {
        $code = sanitizeVariable($request->code);
        $name = sanitizeVariable($request->name);
        $description = sanitizeVariable($request->description);
        $category = sanitizeVariable($request->category);
        $sub_category = sanitizeVariable($request->sub_category);
        $duration = sanitizeVariable($request->duration);
        $created_by  = session()->get('userid');
        // $updated_by  = session()->get('userid');
        $status = '1';
        $medication_array = array(
            'code'   => $code,
            'name'   => $name,
            'description' => $description,
            'category' => $category,
            'sub_category' => $sub_category,
            'duration' => $duration,
            'status' => $status,
            'created_by'  =>$created_by,
            'updated_by' =>$created_by,
        );
        $insert_medication = Medication::create($medication_array);
    }

    //showing list of roles 
    public function MedicationList(Request $request) {
        if ($request->ajax()) {
        $data = Medication::with('users')->latest()->get();
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($row){
        $btn =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
        if($row->status == 1){
            // $btn = $btn. '<a href="changePracticeStatus/'.$row->id.'"><i class="i-Yess i-Yes medicationstatus" title="Active"></i></a>';
            $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_medicationstatus_active"><i class="i-Yess i-Yes" title="Active"></i></a>';
        } else {
            // $btn = $btn.'<a href="changePracticeStatus/'.$row->id.'" class="medstatus"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
            $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_medicationstatus_deactive"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
        }
        return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
        }
        return view('Medication::surgery-list');
    }


    public function CategoryList(Request $request){
        if ($request->ajax()) { 
            $data = Category::with('users')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editCategory" title="Edit"><i class="editform i-Pen-4"></i></a>';  
                    if($row->status == 1){
                            $btn = $btn. '<a href="javascript:void(0)" class="changeCategorystatus_active" data-id="'.$row->id.'"><i class="i-Yess i-Yes" title="Active"></i></a>';
                        }
                    else
                    {
                        $btn = $btn.'<a href="javascript:void(0)" class="changeCategorystatus_deactive" data-id="'.$row->id.'"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
                    }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Medication::category-list');
    }

    public function populateMedication(Request $request) {   
        $id = sanitizeVariable($request->id);
        $org_medication = (Medication::self($id) ? Medication::self($id)->population() : "");
        $result['editMedicationForm'] = $org_medication;    
        return $result;
    }

    // edit users
    public function updateMedication(MedicationUpdateRequest $request) {
        $id= sanitizeVariable($request->id);        
        $code = sanitizeVariable($request->code);
        $name = sanitizeVariable($request->name);
        $description = sanitizeVariable($request->description);
        $category = sanitizeVariable($request->category);
        $sub_category = sanitizeVariable($request->sub_category);
        $duration = sanitizeVariable($request->duration);
        $updated_by  = session()->get('userid');
       $update = array(
           'code'   => $code,
            'name'   => $name,
            'description' => $description,
            'category' => $category,
            'sub_category' => $sub_category,
            'duration' => $duration,
            'updated_by' =>$updated_by
        );
        $update_medication = Medication::where('id',$id)->update($update);
        // echo "1";
    } 

    public function changeMedicationStatus(Request $request) {
        $id = sanitizeVariable($request->id);
         $deviceid = sanitizeVariable($request->deviceid);

        $data = Medication::where('id',$id)->get();
        $status =$data[0]->status;
        if($status==1){
          $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = Medication::where('id',$id)->orderBy('id', 'desc')->update($status);
        }else{
          $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = Medication::where('id',$id)->orderBy('id', 'desc')->update($status);
        }
    }
 
    public function getActiveMedicationList() {
        $medicationList = [];
        $medicationList = Medication::activeMedication();
        return response()->json($medicationList);
    }
    public function getMedicationName() {
        $medicationList = [];
        $medicationList = Medication::all();
        return $medicationList;
    }

    public function createCategory(CategoryRequest $request) {
        $data = [
        'category'=> sanitizeVariable($request->category),     
        'created_by' =>session()->get('userid'), 
        'updated_by' =>session()->get('userid'),
        'status' => 1,
        ];
        $exist=Category::where('category',sanitizeVariable($request->category))->exists();
        if($exist==false){
            $user = Category::create($data);
        }
        else
        {
            return "yes";
        }
       
    }

    public function SubCategoryList(Request $request){
        if ($request->ajax()) {
            $data = SubCategory::with('users','category')->get();
            // dd($data);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editSubCategory" title="Edit"><i class="editform i-Pen-4"></i></a>';  
                if($row->status == 1){
                        $btn = $btn. '<a href="javascript:void(0)" class="changeSubCategorystatus_active" data-id="'.$row->id.'"><i class="i-Yess i-Yes" title="Active"></i></a>';
                    }
                else
                {
                    $btn = $btn.'<a href="javascript:void(0)" class="changeSubCategorystatus_deactive" data-id="'.$row->id.'"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Medication::sub-category-list');
    }

    

    public function updateSubcategory(SubCategoryRequest $request) {
        $data = [
            'category' =>sanitizeVariable($request->category),
            'subcategory'=> sanitizeVariable($request->subcategory),
            'created_by' =>session()->get('userid'),
            'updated_by' =>session()->get('userid'),
            'status' => 1,
            ];
            
        $user = SubCategory::create($data);
    }
    public function createSubCategory(SubCategoryRequest $request) {
        // dd($request);
        // $id= sanitizeVariable($request->hidden_Subcategory_id);
        $data = [
        'category' =>sanitizeVariable($request->category),
        'subcategory'=> sanitizeVariable($request->subcategory),
        'created_by' =>session()->get('userid'),
        'updated_by' =>session()->get('userid'),
        'status' => 1,
        ];
        // dd($data);
        $user = SubCategory::create($data);
    }
    

} 