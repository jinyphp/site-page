<?php

namespace Modules\Fallback\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;

class Drop404 extends Component
{
    public $actions;
    public $uri;


    public function render()
    {
        return view("fallback::livewire.drop404");
    }


}
