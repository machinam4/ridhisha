<?php

namespace App\Http\Controllers;

use App\Models\Players;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MPESAResponseController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function confirmation(Request $request)
    {
        // $data = json_decode($request->getContent());
        // Log::info('confirmation hit');
        // Log::info($request->all());
        Players::Create($request->all());

        return "success";
    }
    public function validation(Request $request)
    {
        // Log::info('validation hit');
        // Log::info($request->all());       
        return  [
                "ResultCode"=> 0,
                "ResultDesc"=> "Accept Service"
        ];
    }
}
