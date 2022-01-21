<?php

namespace App\Http\Livewire;

use App\Models\mpesa;
use Livewire\Component;

class MpesaTable extends Component
{
    protected function getListeners()

    {

        return ['codeAdded' => 'render'];

    }
    public function render()
    {
        //if user is admin return all data
        if (Auth::user()->role == 'Jamii') {
        $players = Players::where('BusinessShortCode', '7296354')->orderBy('TransTime', 'DESC')->limit(50)->get();
        return view('livewire.players-table', ['players' => $players]);
        }else{
        $codes= mpesa::all();
        return view('livewire.mpesa-table',  ['codes'=>$codes]);
        }
    }
}
 