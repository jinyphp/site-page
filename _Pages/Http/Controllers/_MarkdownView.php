<?php

namespace Jiny\Pages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use Jiny\Pages\Http\Controllers\Controller;
class MarkdownView extends Controller
{
    use \Jiny\Table\Http\Livewire\Permit;

    const UPLOAD = "pages";

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
            $mainView = "pages::mark";
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
            //$this->actions['view_content'] = "jinypage::error.permit";
            //$slot = "";
            $slot = view("pages::error.permit");
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
            $filename = resource_path(self::UPLOAD).DIRECTORY_SEPARATOR.$this->actions['view_markdown'].".md";
            $slot []= $this->getMarkdown($filename);
        } else {
            // 컨덴츠 확인하기
            $rows = DB::table("jiny_pages_content")
                ->where('route',"/".$this->actions['route']['uri'])
                ->orderBy('level',"desc")
                ->orderBy('pos',"asc")
                ->get();

            //dd($rows);
            $slot = $this->toTree($rows);
            //dd($slot);

            /*
            foreach($rows as $row) {
                if($row && $row->path) {
                    $filename = resource_path(self::UPLOAD).$row->path;

                    if($row->type == "markdown") {
                        $row->content = $this->getMarkdown($filename);
                    } else if($row->type == "htm") {
                        $row->content = $this->getMarkdown($filename);
                    } else if($row->type == "blade") {
                        $blade = str_replace(".blade.php","",$row->path);
                        $row->blade = "pages".str_replace("/",".",$blade);
                    }

                    $slot []= $row;
                }
            }
            */


        }

        return $slot;
    }

    private function getMarkdown($filename)
    {
        //dd($filename);
        if(file_exists($filename)) {
            $slot = file_get_contents($filename);

            // 조회수 카운트
            //DB::table("jiny_route")->where('route',"/".$this->actions['route']['uri'])->increment('cnt');

        } else {
            $slot = $filename."이 존재하지 않습니다.";
        }

        return $slot;
    }





    private function toTree($rows)
    {
        $tree = [];

        // 배열변환
        foreach ($rows as $row) {
            $id = $row->id;
            foreach ($row as $key => $value) {
                $tree[$id][$key] = $value;
            }
        }

        //dd($tree);

        // 계층이동
        foreach($tree as $i => $item) {
            if($item['level'] != 1) {
                $ref = $item['ref'];
                if($ref == 0) {
                    //
                } else {
                    $tree[$ref]['sub'] []= $tree[$i];
                    unset($tree[$i]);
                }
            }
        }

        //dd($tree);

        return $tree;
    }

}
