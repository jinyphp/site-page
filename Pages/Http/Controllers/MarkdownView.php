<?php

namespace Modules\Pages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use \Jiny\Html\CTag;
use Jiny\Pages\Http\Parsedown;

use Modules\Pages\Http\Controllers\Controller;
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
            $pages = $this->getContent();

        } else {
            // 권한없음
            //$this->actions['view_content'] = "jinypage::error.permit";
            //$slot = "";
            $pages = view("pages::error.permit");
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
                ->orderBy('level',"desc")
                ->orderBy('pos',"asc")->get();

            $tree = [];
            // 배열변환
            foreach ($rows as $row) {
                $id = $row->id;
                foreach ($row as $key => $value) {
                    $tree[$id][$key] = $value;
                }
            }

            //dump($tree);
            // 계층이동
            foreach($tree as $i => $item) {

                if($item['level'] != 1) {
                    //dump($item);
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
        }

        return $slot;
    }



    private function makePage($tree)
    {
        $slot = [];
        //dd($tree);
        foreach($tree as $item) {
            $slot []= $this->section($item);
        }
        return $slot;
    }

    private function section($item)
    {
        $section = new CTag('section', true);
        $section->addClass('element');
        $section->addClass($item['type']);

        $section->setAttribute('data-pos', $item['pos']);
        $section->setAttribute('data-path', $item['path']);
        $section->setAttribute('data-id', $item['id']);
        $section->setAttribute('data-level', $item['level']);
        $section->setAttribute('data-ref', $item['ref']);


        $inner = $this->sectionInner($item);
        $section->addItem($inner);

        return $section;
    }

    private function sectionInner($item)
    {
        $inner = new CTag('div', true);
        $inner->addClass('inner');
        $inner->addClass('layout');

        if($item['type'] == "vertical") {
            /*
            $style .= "grid-template-rows:"; // 세로배치
            */
            $style = "display:flex; flex-direction: column";
        } else {
            $style = "display:grid;";
            $style .= "grid-template-columns:"; // 가로배치
        }

        if(isset($item['sub'])) {
            foreach($item['sub'] as $article) {

                if($article['type'] == 'section'
                    || $article['type'] == 'horizontal'
                    || $article['type'] == 'vertical') {
                    $inner->addItem( $this->section($article) );
                } else {
                    $inner->addItem( $this->article($article) );
                }

                if($item['type'] == "vertical") {

                } else {
                    if($article['width']) {
                        $style .= " ".$article['width']."px";
                    } else {
                        $style .= " 1fr";
                    }
                }

            }
        }

        $style .= ";";
        $inner->setAttribute('style', $style);

        return $inner;
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
            $article->addItem($item['type']." 컨덴츠를 설정해 주세요");
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
