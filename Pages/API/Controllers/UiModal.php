<?php

namespace Modules\Pages\API\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UiModal extends Controller
{

    public function __construct()
    {

    }

    public function confirm(Request $requet)
    {
        return view("pages::modal.confirm");
        return response()->json($_POST);
    }



}
