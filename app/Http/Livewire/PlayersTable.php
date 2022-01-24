<?php

namespace App\Http\Livewire;

use App\Models\mpesa;
use App\Models\Radio;
use App\Models\Players;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PlayersTable extends Component
{
    protected function getListeners()

    {

        return ['getPlayers' => 'render'];

    }
    public function render() 
    {
        //if user is admin return all data
        if (Auth::user()->role == 'Jamii') {
            $players = Players::whereDate('TransTime', date('Y-m-d'))->where('BusinessShortCode', '7296354')->orderBy('TransTime', 'DESC')->limit(20)->get();
            return view('livewire.players-table', ['players' => $players]);
        }
        //if user is admin return all data
        if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Developer') {
            $players = Players::where('BusinessShortCode', '!=', '7296354')->orderBy('TransTime', 'DESC')->limit(20)->get();
            return view('livewire.players-table', ['players' => $players]);
        // if user is radio station, return specific data
        } else {
            $radio = Auth::user()->role;
            $shortcode=Radio::where('name', $radio)->first();
            $shortcode=$shortcode['shortcode'];
            $players = Players::whereDate('TransTime', date('Y-m-d'))->where('BusinessShortCode', $shortcode)->orderBy('TransTime', 'DESC')->limit(20)->get();
            return view('livewire.players-table', ['players' => $players]);
        }
    }
}
