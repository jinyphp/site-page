<?php
namespace Jiny\Site\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;

use Jiny\Members\Http\Controllers\CrudController;
class AdminPageTrans extends Controller
{
    public function index()
    {
        return view("jiny-site-page::admin.trans");
    }




}
