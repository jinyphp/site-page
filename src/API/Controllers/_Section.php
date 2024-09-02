<?php
namespace Jiny\Site\Page\API\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Section extends Controller
{
    const UPLOAD = "pages";

    public function __construct()
    {

    }

    public function delete(Request $requet)
    {
        $uploaded = [];
        $uploaded['post'] = $_POST;

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

        return response()->json($uploaded);
    }

    public function pos(Request $requet)
    {
        $uploaded = [];
        //$uploaded['post'] = $_POST;
        // $uploaded = [];

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

        // foreach($_POST['pos'] as $id => $pos){
        //     DB::table("jiny_pages_content")->where('id', $id)->update(['pos'=>$pos]);
        // }

        // return response()->json($uploaded);
    }

    /**
     * widget,section 드래그 이동시
     */
    public function move(Request $requet)
    {
        $uploaded = [];
        $uploaded['post'] = $_POST;

        // 위젯 새로등록
        if($_POST['id'] == 0) {
            $url = parse_url($_POST['_uri']);

            if(isset($_POST['type'])) {
                $type = $_POST['type'];
            } else {
                $type = "html"; // 기본값
            }

            $id = DB::table("jiny_pages_content")->insertGetId([
                'enable' => 1,
                'route' => $url['path'],
                'type' => $type,
                'ref' => $_POST['ref'],
                'level' => $_POST['level'],
                'pos' => $_POST['pos'],

                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
            $uploaded['id'] = $id;
        }
        // 위젯 위치이동
        else {
            DB::table("jiny_pages_content")
            ->where('id', $_POST['id'])
            ->update([
                'ref'=>$_POST['ref'],
                'level'=>$_POST['level'],
                'pos'=>$_POST['pos']
            ]);
        }

        return response()->json($uploaded);
    }

    public function resize(Request $requet)
    {
        $uploaded = [];
        $uploaded['post'] = $_POST;

        $width = intval(str_replace("px","",$_POST['width']));
        $height = intval(str_replace("px","",$_POST['height']));


        DB::table("jiny_pages_content")
            ->where('id', $_POST['id'])
            ->update([
                'width'=>$width,
                'height'=>$height
            ]);

        return response()->json($uploaded);
    }
























    public function dropzone(Request $requet)
    {
        $uploaded = [];

        if (!empty($_FILES['file']['name'][0])) {
            foreach ($_FILES['file']['name'] as $pos => $name) {
                $info = pathinfo($name);
                if($info['extension'] == "md") {
                    // markdown upload
                    $uploaded = $this->markdown($pos, $name);
                    $uploaded['info'] = $info;

                }
            }
        }

        return response()->json($uploaded);
    }

    private function markdown($pos, $name)
    {
        // 경로 저장소 처리
        $url = parse_url($_POST['_uri']);
        $path = resource_path('markdown').$url['path'];
        $path = str_replace("/",DIRECTORY_SEPARATOR,$path);
        if (!is_dir($path)) mkdir($path, 755, true);

        $filename = $path.DIRECTORY_SEPARATOR.$name;
        $source = $_FILES['file']['tmp_name'][$pos];
        if( move_uploaded_file($source, $filename) ){
            $uploaded []= [
                'name' => $name,
                'url' => parse_url($_POST['_uri']),
                'path' => $path
            ];

            $uploaded['file']= $filename;
        }

        // DB 저장
        // 시간정보 생성
        $forms['created_at'] = date("Y-m-d H:i:s");
        $forms['updated_at'] = date("Y-m-d H:i:s");

        $forms['enable'] = 1;
        $forms['route'] = $url['path'];
        $forms['type'] = "markdown:markdown";
        $forms['path'] = $url['path']."/".$name;

        // 데이터 삽입
        DB::table("jiny_route")->insertGetId($forms);
        $uploaded['forms'] = $forms;

        // 마크다운 파일path 등록
        // 데이터 삽입
        DB::table("jiny_pages_markdown")->insertGetId([
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s"),
            'enable'=>1,
            'route'=>$url['path'],
            'path'=>$url['path']."/".$name,
            'pos'=>1
        ]);

        return $uploaded;
    }





}
