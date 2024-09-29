<?php
namespace Jiny\Site\Page\API\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageWidgetDrag extends Controller
{
    public function __construct()
    {

    }

    public function pos(Request $requet)
    {
        $uploaded = [];

        $uri = parse_url($_POST['_uri'])['path'];
        $uploaded['uri'] = $uri;

        $path = resource_path("actions");
        $path .= str_replace('/',DIRECTORY_SEPARATOR,$uri);
        $path .= ".json";
        $uploaded['path'] = $path;

        if(file_exists($path)) {
            $actions = json_file_decode($path);
        } else {
            $actions = [];
        }


        //새로운 저장 목록 변환
        $temp = [];
        foreach($_POST['pos'] as $i) {
            $temp []= $actions['widgets'][$i];
        }

        $actions['widgets'] = $temp; // 수정된 순서 재저장
        json_file_encode($path, $actions);

        return response()->json($uploaded);

        // 경로 저장소 처리
        $slot = www_slot();
        $uploaded['slot'] = $slot;

        $path = resource_path("www");
        if($slot) {
            $path .= DIRECTORY_SEPARATOR.$slot;
        }



        $path .= str_replace("/", DIRECTORY_SEPARATOR, $uri);
        $uploaded['path'] = $path;

        $uploaded['pos'] = $_POST['pos'];


        // json 파일 읽기
        $file = $uploaded['path'].DIRECTORY_SEPARATOR."widgets.json";
        if(file_exists($file)) {
            $widgets = json_file_decode($file);
        } else {
            $widgets = [];
        }

        //새로운 저장 목록 변환
        $temp = [];
        foreach($_POST['pos'] as $i) {
            $temp []= $widgets[$i];
        }

        // 변경된 순서 저장
        json_file_encode($file, $temp);

        return response()->json($uploaded);

    }

    public function delete(Request $requet)
    {
        $uploaded = [];
        $uploaded['post'] = $_POST;

        return response()->json($uploaded);
    }




}
