<?php

namespace Jiny\Pages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use Jiny\Pages\Http\Controllers\Controller;
class PostView extends Controller
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
            $mainView = "jinypage::post";
        }

        // 테마지정
        if (isset($this->actions['theme']) && $this->actions['theme']) {
            xTheme()->setTheme($this->actions['theme']);
        }

        if($this->permit['read']) {
            $slot = $this->getPost();

        } else {
            // 권한없음
            $slot = view("jinypage::error.permit");

        }

        if(empty($row)) $row = [];

        return view($mainView,[
            'actions'=>$this->actions,
            'slot'=>$slot,
            'row'=>$row
        ]);
    }

    private function getPost()
    {
        // 필드 변수값들 유효성 검사...
        if(isset($this->actions['post_table']) && $this->actions['post_table']) {
        } else {
            return "post 테이블을 설정해 주세요.";
        }

        if(isset($this->actions['post_id']) && $this->actions['post_id']) {
        } else {
            return "post id를 설정해 주세요.";
        }

        if(isset($this->actions['post_id']) && $this->actions['post_id']) {
        } else {
            return "post 필드를 설정해 주세요.";
        }

        // 데이터 읽기
        $row = DB::table($this->actions['post_table'])
            ->where('enable',true)
            ->where('id',$this->actions['post_id'])->first();

        if($row) {
            // 조회수 카운트
            DB::table("jiny_route")->where('route',"/".$this->actions['route']['uri'])->increment('cnt');
            return $row->{$this->actions['post_field']};
        } else {
            return "데이터가 없습니다.";
        }
    }


}
