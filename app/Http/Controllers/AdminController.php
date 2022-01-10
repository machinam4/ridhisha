<?php

namespace App\Http\Controllers;

use App\Models\mpesa;
use App\Models\Radio;
use App\Models\Players;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MPESAController;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('cors');
    }

    public function index(){
        //if user is admin return all data
        if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Developer') {
            $players = Players::orderBy('TransTime', 'DESC')->limit(50)->get();
            $totalAmount = Players::get()->sum('TransAmount');
            return view('admin.dashboard', ['players' => $players, 'totalAmount'=>$totalAmount]);
        // if user is radio station, return specific data
        } else {
            $radio = Auth::user()->role;
            $shortcode=Radio::where('name', $radio)->first();
            $shortcode=$shortcode['shortcode'];
            $players = Players::where('BusinessShortCode', $shortcode)->limit(50)->get();
            $totalAmount = Players::where('BusinessShortCode', $shortcode)->sum('TransAmount');
            return view('admin.dashboard', ['players' => $players, 'totalAmount'=>$totalAmount]);
        }
    }

    public function players(){  
         //if user is admin return all data
         if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Developer') {
            $players = Players::get()->count();
            $totalAmount = Players::get()->sum('TransAmount');
            return view('admin.players', ['players' => $players, 'totalAmount'=>$totalAmount]);
        // if user is radio station, return specific data
        } else {
            $radio = Auth::user()->role;
            $shortcode=Radio::where('name', $radio)->first();
            $shortcode=$shortcode['shortcode'];
            $players = Players::where('BusinessShortCode', $shortcode)->get()->count();
            $totalAmount = Players::where('BusinessShortCode', $shortcode)->sum('TransAmount');
            return view('admin.players', ['players' => $players, 'totalAmount'=>$totalAmount]);
        }
    }

    public function sms(){

        $sms = new SMSController;
        $getsms= json_decode($sms->getSMS())->Data;
        return view('admin.sms', ['smss'=>$getsms]);
    }
    public function mpesa(){
        return view('admin.mpesa');
    }
    public function addCode(Request $request)
    {
        $data = [
            'shortcode'=>$request->input('shortcode'),
            'name'=>$request->input('name'),
            'username'=>$request->input('username'),
            'key'=>$request->input('key'),
            'secret'=>$request->input('secret'),
            'passkey'=>$request->input('passkey'),
            'b2cPassword'=>$request->input('b2cPassword'),
            'created_by'=>Auth::user()->name,
        ]; 
        try {
            $MPESAController = new MPESAController;
            $MPESAController->registerUrl($data);
            mpesa::create($data);
        } catch (\Throwable $th) {
            return [
                'message' => 'error registering shortcode, please confirm details provided',
                'type'=>'error'
            ];
        }        
        return [
            'message' => 'success registering shortcode',
            'type'=>'success'
        ];
    }

    public function radio(){
        $radios= Radio::all();
        return view('admin.radio', ['radios'=>$radios]);
    }
    public function addRadio(Request $request)
    {
        $data = [
            'name'=>$request->input('name'),
            'shortcode'=>$request->input('shortcode'),
            'store'=>$request->input('store'),
            'created_by'=>Auth::user()->name,
        ];
        try {
            Radio::create($data);
        } catch (\Throwable $th) {
            return [
                'message' => 'error registering Radio, please confirm details provided',
                'type'=>'error'
            ];
        }        
        return [
            'message' => 'success registering Radio',
            'type'=>'success'
        ];
    }
}
