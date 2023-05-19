<?php

namespace RCare\System\Models;
use RCare\RCareAdmin\AdminPackages\Users\src\Models\User;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    public const HASH_LENGTH = 64;

    protected $fillable = [
        "id",
        "email",
        "token"
    ];

    public static function createRequest($Auth, $login_as)
    { 
        $token = createHash(self::HASH_LENGTH);
        // dd($Auth['email']);
       if ($login_as=='1'){
                    $rcare_user = User::where('email',$Auth['email'])->update([
                        "token" => $token
                    ]);
                    // dd($rcare_user);

        }elseif ($login_as=='2') {
                    $rcare_user = Users::where('email',$Auth['email'])->update([
                        "token" => $token
                    ]);
                    // dd($rcare_user);
        }
          
    }

}
