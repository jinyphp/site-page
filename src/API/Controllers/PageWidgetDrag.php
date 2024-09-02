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

        // 경로 저장소 처리
        $slot = www_slot();
        $uploaded['slot'] = $slot;

        $path = resource_path("www");
        if($slot) {
            $path .= DIRECTORY_SEPARATOR.$slot;
        }

        $uri = parse_url($_POST['_uri'])['path'];
        $uploaded['uri'] = $uri;

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

        /*
        if(isset($_POST['path'])) {
            $rows = DB::table("jiny_pages_content")->where('path', $_POST['path'])->get();
        } else if(isset($_POST['id'])) {
            $rows = DB::table("jiny_pages_content")->where('id', $_POST['id'])->get();
        }

        if($rows && count($rows) == 1) {
            // 파일만 삭제, 섹션은 제외함
            if($rows[0]->path) {
                $path = resource_path(self::UPLOAD).$rows[0]->path; //$_POST['path'];
                $uploaded['pathname'] = $path;
                if(file_exists($path)) {
                    $uploaded['path'] = $path;
                    unlink($path);
                } else {
                    $uploaded['message'] = "파일이 존재하지 않습니다.";
                }
            }

        }

        if($rows) {
            DB::table("jiny_pages_content")->where('id', $_POST['id'])->delete();
            //DB::table("jiny_pages_markdown")->where('path', $_POST['path'])->delete();
            $uploaded['message2'] = $_POST['id']." deleted";
        }
        */

        return response()->json($uploaded);
    }




}
