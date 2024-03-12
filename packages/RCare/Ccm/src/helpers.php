<?php

use Carbon\Carbon;
use Illuminate\Http\Request;
use RCare\Rpm\Models\MailTemplate;
use Illuminate\Support\Facades\Log;

function getCallScriptsById(Request $request){
Log::info($request);
    $id = $request->id;
    $scripts = MailTemplate::where('id',$id)->get(); //

    return $scripts;
}