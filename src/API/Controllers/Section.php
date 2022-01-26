<?php

namespace Jiny\Pages\API\Controllers;

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

        $rows = DB::table("jiny_pages_content")->where('path', $_POST['path'])->get();
        if(count($rows) == 1) {
            $path = resource_path(self::UPLOAD).$_POST['path'];
            $uploaded['pathname'] = $path;
            if(file_exists($path)) {
                $uploaded['path'] = $path;
                unlink($path);
            } else {
                $uploaded['message'] = "파일이 존재하지 않습니다.";
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
        $uploaded['post'] = $_POST;

        foreach($_POST['pos'] as $id => $pos){
            DB::table("jiny_pages_content")->where('id', $id)->update(['pos'=>$pos]);
        }

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
