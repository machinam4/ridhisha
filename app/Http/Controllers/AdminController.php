<?php

namespace App\Http\Controllers;

use App\Models\mpesa;
use App\Models\Players;
use Illuminate\Http\Request;
use App\Http\Controllers\MPESAController;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('cors');
    }

    public function index(){

        $players = Players::orderBy('TransTime', 'DESC')->limit(50)->get();

        return view('admin.dashboard', ['players' => $players]);
    }

    public function players(){
        $players = Players::orderBy('TransTime', 'DESC')->limit(50)->get();
        return view('admin.players', ['players' => $players]);
    }

    public function sms(){

        $sms = new SMSController;
        $getsms= json_decode($sms->getSMS())->Data;
        return view('admin.sms', ['smss'=>$getsms]);
    }
    public function mpesa(){
        $codes= mpesa::all();
        return view('admin.mpesa', ['codes'=>$codes]);
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
}
