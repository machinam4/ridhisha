<?php

namespace App\Http\Livewire;

use App\Models\mpesa;
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
        if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Developer') {
            $players = Players::orderBy('TransTime', 'DESC')->limit(50)->get();
            return view('livewire.players-table', ['players' => $players]);
        // if user is radio station, return specific data
        } else {
            $radio = Auth::user()->role;
            $shortcode=mpesa::where('radio', $radio)->first();
            $shortcode=$shortcode['shortcode'];
            $players = Players::where('BusinessShortCode', $shortcode)->limit(50)->get();
            return view('livewire.players-table', ['players' => $players]);
        }
    }
}