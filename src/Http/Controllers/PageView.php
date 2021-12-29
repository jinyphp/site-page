<?php

namespace Jiny\Pages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use Jiny\Pages\Http\Controllers\Controller;
class PageView extends Controller
{
    use \Jiny\Table\Http\Livewire\Permit;

    public function __construct()
    {
        parent::__construct();
        $this->permitCheck();
    }


    /**
     *
     */
    public function index(...$slug)
    {
        if (isset($this->actions['view_layout'])) {
            $mainView = $this->actions['view_layout'];
        } else {
            $mainView = "jinypage::main";
        }

        // 테마지정
        if (isset($this->actions['theme']) && $this->actions['theme']) {
            xTheme()->setTheme($this->actions['theme']);
        }

        if($this->permit['read']) {
            // 조회수 카운트
            //dd($this->actions['route']['uri']);
            DB::table("jiny_route")->where('route',"/".$this->actions['route']['uri'])->increment('cnt');

        } else {
            // 권한없음
            $this->actions['view_content'] = "jinypage::error.permit";
        }

        return view($mainView,['actions'=>$this->actions]);
    }

}
