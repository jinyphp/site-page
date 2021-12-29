<?php

namespace Jiny\Pages\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;

use Jiny\Members\Http\Controllers\CrudController;

class AdminPages extends Controller
{
    public function index()
    {
        //$dirTree['path'] = $path;
        $path = "docs";
        $dirTree = $this->dirTree($path);
        //dd($dirTree);

        return view("jinypage::admin.pages",['tree'=>$this->htmlUlTree($dirTree)]);
    }

    private function htmlUlTree($tree)
    {
        if(is_array($tree)) {
            $ul = "<ul>";
            foreach($tree as $key => $item) {
                if(is_array($item)) {
                    $ul .= "<li>".
                        "<div><b>".$key."</b></div>".
                        $this->htmlUlTree($item)."</li>";
                } else {
                    $ul .= "<li>".$item."</li>";
                }
            }
            $ul .= "</ul>";
        }

        return $ul;
    }

    private function dirTree($path)
    {
        $_path = resource_path($path);
        $tree = scandir($_path);
        //return $tree;

        $arr = [];
        for($i=0;$i<count($tree);$i++) {
            if($tree[$i] == "." || $tree[$i] == "..") {
                continue;
            }

            if($tree[$i][0] == ".") {
                continue;
            }

            if(is_dir($_path.DIRECTORY_SEPARATOR.$tree[$i])) {
                $arr[$tree[$i]] = $this->dirTree($path.DIRECTORY_SEPARATOR.$tree[$i]);
                continue;
            }

            $arr[$tree[$i]] = $tree[$i];
        }

        krsort($arr); // 키 내림차순 정렬
        return $arr;

    }
}
