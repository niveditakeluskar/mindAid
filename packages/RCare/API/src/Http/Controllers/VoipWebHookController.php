<?php

namespace RCare\API\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use RCare\API\Models\VoipWebhook;
use RCare\API\Models\Partner;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class VoipWebHookController extends Controller
{
	/**
	 * @param Request $request
	 * @return json
	 */

	/*protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }*/


	public function voipwebhookHandler(Request $request)
	{
		$headers = $request->header('Authorization');
		//dd($headers);
		$jwttoken = substr($headers, 7);

		$checkTokenExist = Partner::tokenExists($jwttoken);
		if ($checkTokenExist == true) {
			$content = $request->all();
			$callid = $request->callid;
			$caller = $request->caller;
			$agent = $request->agent;
			$direction = $request->direction;
			$datetime = $request->datetime;
			//echo $datetime;

			$dtarr = preg_split('/\s+/', $datetime);;
			//print_r($dtarr);

			$Y = substr($dtarr[0],  -4);
			$M = substr($dtarr[0], 2, 2);
			$D = substr($dtarr[0], 0, 2);

			$time = date("G:i", strtotime($dtarr[1]));
			$dateString = $Y . '-' . $M . '-' . $D . ' ' . $time . ':00';
			$recordingfilename = $request->recordingfilename;
			// dd($content); 
			$newcontent = json_encode($content);
			$currenturl = url()->full();
			$data = array(
				'content' => $newcontent,
				'partner' => 'mrvoip',
				'status' => '0',
				'datetime' => $dateString,
				'callid' => $callid,
				'caller' => $caller,
				'agent' => $agent,
				'direction' => $direction,
				'recordingfilename' => $recordingfilename,
				'url'  =>  $currenturl
			);

			$result = VoipWebhook::create($data);
			if ($result) {
				return response()->json(["status" => 200, "message" => "success"]);
			} else {
				return response()->json(["status" => 400, "message" => "failed"]);
			}
		} else {
			return response()->json(["status" => 401, "message" => "Unauthorized"]);
		}
	}
}
