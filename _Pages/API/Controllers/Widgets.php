<?php

namespace Modules\Pages\API\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Widgets extends Controller
{
    public function __construct()
    {

    }


    public function index(Request $requet)
    {
        return view("pages::widgets.index");
    }
}
