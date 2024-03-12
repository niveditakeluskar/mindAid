<?php

namespace RCare\RCareAdmin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('Theme::dashboard.dashboard');
        // return "DashboardController";
        // return view("RCareAdmin::home");
    }

    public function test()
    {
        // return "from dashboard controller";
        return view("RCareAdmin::home");
    }
}
