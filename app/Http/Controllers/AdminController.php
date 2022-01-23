<?php

namespace App\Http\Controllers;

use App\Models\mpesa;
use App\Models\Radio;
use App\Models\Players;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        //if user is munene return all the hidden shortcode
        if (Auth::user()->role == 'Jamii') {
            // $players = Players::orderBy('TransTime', 'DESC')->limit(50)->get();
            $players = Players::select(
                DB::raw("(sum(TransAmount)) as TransAmount"),
                DB::raw("(DATE_FORMAT(TransTime, '%d-%M-%Y')) as TransTime")
                )->where('BusinessShortCode', '7296354')->groupBy(DB::raw("DATE_FORMAT(TransTime, '%d-%M-%Y')"))->get();
            $totalAmount = Players::where('BusinessShortCode', '7296354')->get()->sum('TransAmount');
            $totalToday = Players::where('BusinessShortCode', '7296354')->whereDate('TransTime', date('Y-m-d'))->sum('TransAmount');
            return view('admin.dashboard', ['players' => $players, 'totalAmount'=>$totalAmount, 'totalToday'=>$totalToday]); 
        }
        //if user is admin return all data
        if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Developer') {
            // $players = Players::orderBy('TransTime', 'DESC')->limit(50)->get();
            $players = Players::select(
                DB::raw("(sum(TransAmount)) as TransAmount"),
                DB::raw("(DATE_FORMAT(TransTime, '%d-%M-%Y')) as TransTime")
                )->where('BusinessShortCode', '!=', '7296354')->groupBy(DB::raw("DATE_FORMAT(TransTime, '%d-%M-%Y')"))->get();
            $totalAmount = Players::where('BusinessShortCode', '!=', '7296354')->get()->sum('TransAmount');
            $totalToday = Players::where('BusinessShortCode', '!=', '7296354')->whereDate('TransTime', date('Y-m-d'))->sum('TransAmount');
            return view('admin.dashboard', ['players' => $players, 'totalAmount'=>$totalAmount, 'totalToday'=>$totalToday]);
        // if user is radio station, return specific data
        } else {
            $radio = Auth::user()->role;
            $shortcode=Radio::where('name', $radio)->first();
            $shortcode=$shortcode['shortcode'];
            $players = Players::select(
                DB::raw("(sum(TransAmount)) as TransAmount"),
                DB::raw("(DATE_FORMAT(TransTime, '%d-%M-%Y')) as TransTime")
                )->groupBy(DB::raw("DATE_FORMAT(TransTime, '%d-%M-%Y')"))->where('BusinessShortCode', $shortcode)->get();
            $totalToday = Players::whereDate('TransTime', date('Y-m-d'))->where('BusinessShortCode', $shortcode)->sum('TransAmount');
            $totalAmount = Players::where('BusinessShortCode', $shortcode)->sum('TransAmount');
            return view('admin.dashboard', ['players' => $players, 'totalAmount'=>$totalAmount, 'totalToday'=>$totalToday]);
        }
    }

    public function players(){  
         //if user is munene return all the hidden shortcode
         if (Auth::user()->role == 'Jamii') {
            $players = Players::whereDate('TransTime', date('Y-m-d'))->where('BusinessShortCode', '7296354')->get()->count();
            $totalAmount = Players::whereDate('TransTime', date('Y-m-d'))->where('BusinessShortCode', '7296354')->sum('TransAmount');
            return view('admin.players', ['players' => $players, 'totalAmount'=>$totalAmount]);
        }
         //if user is admin return all data
         if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Developer') {
            $players = Players::where('BusinessShortCode', '7296354')->get()->count();
            $totalAmount = Players::where('BusinessShortCode', '7296354')->get()->sum('TransAmount');
            return view('admin.players', ['players' => $players, 'totalAmount'=>$totalAmount]);
        // if user is radio station, return specific data
        } else {
            $radio = Auth::user()->role;
            $shortcode=Radio::where('name', $radio)->first();
            $shortcode=$shortcode['shortcode'];
            $players = Players::whereDate('TransTime', date('Y-m-d'))->where('BusinessShortCode', $shortcode)->get()->count();
            $totalAmount = Players::whereDate('TransTime', date('Y-m-d'))->where('BusinessShortCode', $shortcode)->sum('TransAmount');
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
            $registration = $MPESAController->generateAccessToken($data['key'], $data['secret']);
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
    public function URLregister($id)
    {
        $code = mpesa::find($id);
        $data = [
            'shortcode'=>$code->shortcode,
            'key'=>$code->key,
            'secret'=>$code->secret,
        ]; 
        $MPESAController = new MPESAController;
        $registration = $MPESAController->registerURL($data);

        return $registration;
}
}
