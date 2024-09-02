<?php
namespace Jiny\Site\Page\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use Jiny\Pages\Http\Controllers\Controller;
class HtmlView extends Controller
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
        // 출력 레이아웃
        if (isset($this->actions['view_layout'])) {
            $mainView = $this->actions['view_layout'];
        } else {
            $mainView = "jiny-site-page::html";
        }

        // 사이트 테마 지정
        if (isset($this->actions['theme']) && $this->actions['theme']) {
            xTheme()->setTheme($this->actions['theme']);
        }

        // 권환 확인
        if($this->permit['read']) {
            $slot = $this->getContent();

        } else {
            // 권한없음
            //$this->actions['view_content'] = "jiny-site-page::error.permit";
            //$slot = "";
            $slot = view("jiny-site-page::error.permit");
        }

        return view($mainView,[
            'actions'=>$this->actions,
            'slot'=>$slot
        ]);
    }

    private function getContent()
    {
        $slot = [];
        // Actions 설정 파일 정보
        if(isset($this->actions['view_markdown']) && $this->actions['view_markdown']) {
            $filename = resource_path('markdown').DIRECTORY_SEPARATOR.$this->actions['view_markdown'].".htm";
            $slot []= $this->getMarkdown($filename);
        } else {
            // 동적 라우트 테이블에서 확인하기
            $rows = DB::table("jiny_pages_markdown")->where('route',"/".$this->actions['route']['uri'])->get();

            foreach($rows as $row) {
                if($row && $row->path) {
                    $filename = resource_path('html').$row->path;
                    $slot []= $this->getMarkdown($filename);
                }
            }
        }

        return $slot;
    }

    private function getMarkdown($filename)
    {
        if(file_exists($filename)) {
            $slot = file_get_contents($filename);

            // 조회수 카운트
            DB::table("jiny_route")->where('route',"/".$this->actions['route']['uri'])->increment('cnt');

        } else {
            $slot = $this->actions['view_markdown'].".htm"."이 존재하지 않습니다.";
        }

        return $slot;
    }

}
