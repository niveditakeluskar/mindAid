<?php
namespace RCare\Org\OrgPackages\Users\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class OrgUserStatusUpdateRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return validationRules(True,
            [
                'status'             => 'required|integer'
            ]
        );

    }

    public static function self($id)
    {   $id  = sanitizeVariable($id);
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    }

    // public static function activeReports()
    // {   
    //     return self::where('status', 1)->orderBy('created_at', 'desc')->first();
    // }
    public static function activeReports()
    {
        return self::where("status", 1)->orderBy('created_at','desc')->get();
    }
    
}