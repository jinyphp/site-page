<?php

namespace Jiny\Pages\Http;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Webuni\FrontMatter\FrontMatter;
use Jiny\Pages\Http\Parsedown;
use Illuminate\Support\Facades\View;

class PageController extends Controller
{
    // 마크다운이 존재하는 페이지
    public $rootpath = "docs"; 

    public function __construct($path=null)
    {
        if ($path) {
            $this->rootpath = $path;
        }        
    }

    public function index(...$slug)
    {
        $this->setPath($slug);
        if($dir = $this->isDirPath($slug)) {
            $path = $this->dirPath();
        } else {
            // 파일경로, 타입검사
            $path = $this->extentionType($this->path);
        }

        if($this->ext == ".md") {
            ## 마크다운
            return $this->markdown($path);
        } else 
        if($this->ext == ".html" || $this->ext == ".htm") {
            ## html
            $string = implode(".",$slug);
            if($dir) {
                $string .= ".".$this->index;
            } 
            return view($string);
        } else 
        if($this->ext == ".blade.php") {
            ## 블레이드 파일
            $string = implode(".",$slug);
            if($dir) {
                $string .= ".".$this->index;
            } 
            return view($string);
        } else 
        if($this->ext == ".php") {
            return $this->runPHP($path);
        }

        return "can not find markdown file";
    }


    private function runPHP($path)
    {
        return include($path);
    }


    private $path;
    private $ext;
    private $index;

    private function setPath($slug)
    {
        $string = implode(DIRECTORY_SEPARATOR,$slug);
        $this->path = resource_path($this->rootpath.DIRECTORY_SEPARATOR.$string);
        return $this;
    }


    private function isDirPath($slug)
    {
        if(is_dir($this->path)) {
            return true;
        }
        return false;
    }


    private function extentionType($path)
    {
        $type = [".md", ".html", ".htm", ".blade.php", ".php"];
        foreach($type as $item) {
            if (file_exists($path.$item)) {
                $this->ext = $item;
                return $path.$item;
            }
        }

        return false;
    }


    private function dirPath(){
        $file = ["index","readme"];

        // 디렉터리, 타입검사            
        foreach($file as $item) {
            if($path = $this->extentionType($this->path.DIRECTORY_SEPARATOR.$item)) {
                $this->index = $item;
                return $path;
            }
        }

        return false;
    }


    private function markdown($path)
    {
        $text = file_get_contents($path);

        // frontmatter ---
        list($data, $content) = $this->frontMatter($text);
        $data['slot'] = $content;

        $resource = $this->layoutTheme($data);
        return view($resource, $data);
    }

    
    private function frontMatter($text)
    {
        $frontMatter = new FrontMatter();
        $document = $frontMatter->parse($text);
        $data = $document->getData();
        $content = $document->getContent();

        return [$data, $content];
    }


    private function layoutTheme($data)
    {
        // 테마 페키지가 설치되어 있는 경우
        if(function_exists('theme')) {
            // forntmatter 설정된 
            // resource layout을 이용
            if (isset($data['theme'])) {

                theme()->setTheme($data['theme']);

                // 사용자 레이아웃 지정확인
                if (isset($data['layout'])) {
                    $layout = $data['layout'];
                } else {
                    $layout = "markdown";
                }
    
                $resource = "theme.".$data['theme'].".".$layout;
                if (View::exists($resource)) {
                    return $resource;
                }
            }
        }

        return "jinypage::markdown";
    }

}
