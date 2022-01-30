<?php

namespace Jiny\Pages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use \Jiny\Html\CTag;
use Jiny\Pages\Http\Parsedown;

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
            $mainView = "jinypage::mark";
        }

        // 사이트 테마 지정
        if (isset($this->actions['theme']) && $this->actions['theme']) {
            xTheme()->setTheme($this->actions['theme']);
        }

        // 권환 확인
        if($this->permit['read']) {
            $pages = $this->getContent();

        } else {
            // 권한없음
            //$this->actions['view_content'] = "jinypage::error.permit";
            //$slot = "";
            $pages = view("jinypage::error.permit");
        }


        return view($mainView,[
            'actions'=>$this->actions,
            'pages'=>$pages
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
                ->orderBy('pos',"asc")->get();

            $tree = [];
            // 배열변환
            foreach ($rows as $row) {
                $id = $row->id;
                foreach ($row as $key => $value) {
                    $tree[$id][$key] = $value;
                }
            }

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
            $slot = $this->makePage($tree);
            return $slot;


            /*
            foreach($rows as $row) {

            }
            */
        }

        return $slot;
    }



    private function makePage($tree)
    {
        $slot = [];
        foreach($tree as $item) {
            $section = new CTag('section', true);
            $section->addClass('element');
            $section->setAttribute('data-pos', $item['pos']);
            $section->setAttribute('data-path', $item['path']);
            $section->setAttribute('data-id', $item['id']);
            $section->setAttribute('data-level', $item['level']);
            $section->setAttribute('data-ref', $item['ref']);

                $grip = new CTag('span', true);
                $grip->addHtml('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grip-vertical" viewBox="0 0 16 16">
                    <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                </svg>');
                $grip->addClass('section-grap');
                $section->addItem($grip);



                $inner = new CTag('div', true);
                $inner->addClass('inner');

                //section css
                $style = "display:grid;";
                if(isset($item['sub'])) {
                    $style .= "grid-template-columns:";
                    foreach($item['sub'] as $article) {
                        /*
                        $gridCell = new CTag('div', true);
                        $gridCell->addClass('cell');
                        $gridCell->addItem($this->article($article));

                        $inner->addItem( $gridCell );
                        */
                        $inner->addItem( $this->article($article) );

                        if($article['width']) {
                            $style .= " ".$article['width']."px";
                        } else {
                            $style .= " 1fr";
                        }
                    }
                    $style .= ";";
                }
                $inner->setAttribute('style', $style);

            $section->addItem($inner);
            $slot []= $section;
        }
        return $slot;
    }

    private function article($item)
    {
        $article = new CTag('article', true);
        $article->addClass("widget element");

        $article->setAttribute('data-pos', $item['pos']);
        $article->setAttribute('data-path', $item['path']);
        $article->setAttribute('data-id', $item['id']);
        $article->setAttribute('data-level', $item['level']);
        $article->setAttribute('data-ref', $item['ref']);

        //dd($item);
        if($item && $item['path']) {
            $content = "";

            $filename = resource_path(self::UPLOAD).$item['path'];

            if($item['type'] == "markdown") {
                $content = $this->getMarkdown($filename);

                $Parsedown = new Parsedown();
                $markdown = $Parsedown->parse($content);
                $article->addHtml($markdown);

            } else if($item['type'] == "html" || $item['type'] == "htm") {
                //dd($filename);
                $content = $this->getMarkdown($filename);
                $article->addHtml($content);

            } else if($item['type'] == "blade") {
                $blade = str_replace(".blade.php","",$item['path']);
                $item['blade'] = "pages".str_replace("/",".",$blade);
                //dd($item['blade']);
                $article->addHtml( view($item['blade']) );
            } else if($item['type'] == "image") {
                //dd($item['path']);
                $img = new CTag('img', false);
                //$filename = public_path(self::UPLOAD).$item['path'];
                $img->setAttribute('src', "/images".$item['path'] );
                $img->setAttribute('width',"100%");
                $article->addItem( $img );
            }

        } else {
            // 소스 path 없음.
            $article->addItem("컨덴츠를 설정해 주세요");
        }

        return $article;
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




}
