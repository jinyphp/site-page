<?php

namespace Jiny\Pages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use Jiny\Pages\Http\Controllers\Controller;
class MarkdownView extends Controller
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
            $mainView = "jinypage::mark";
        }

        // 테마지정
        if (isset($this->actions['theme']) && $this->actions['theme']) {
            xTheme()->setTheme($this->actions['theme']);
        }

        if($this->permit['read']) {
            if(isset($this->actions['view_content']) && $this->actions['view_content']) {
                $path = resource_path('markdown');
                $filename = $path.DIRECTORY_SEPARATOR.$this->actions['view_content'].".md";
                if(file_exists($filename)) {
                    $slot = file_get_contents($filename);

                    // 조회수 카운트
                    DB::table("site_route")->where('route',"/".$this->actions['route']['uri'])->increment('cnt');

                } else {
                    $slot = $this->actions['view_content'].".md"."이 존재하지 않습니다.";
                }
            } else {
                $slot = "마크다운 파일을 설정해 주세요.";
            }

        } else {
            // 권한없음
            //$this->actions['view_content'] = "jinypage::error.permit";
            //$slot = "";
            $slot = view("jinypage::error.permit");
        }

        return view($mainView,[
            'actions'=>$this->actions,
            'slot'=>$slot
        ]);
    }

}
