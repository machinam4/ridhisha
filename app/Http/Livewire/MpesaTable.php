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
        $codes= mpesa::all();
        return view('livewire.mpesa-table',  ['codes'=>$codes]);
    }
}
 