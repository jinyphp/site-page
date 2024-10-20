<?php

namespace Modules\Pages\API\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Pages extends Controller
{
    const UPLOAD = "pages";

    public function __construct()
    {

    }

    /**
     * widget 드래그 이동시
     */
    public function widget(Request $requet)
    {
        $uploaded = [];
        $uploaded['post'] = $_POST;
        $url = parse_url($_POST['_uri']);
        $uploaded['message'] = "drag 위치 이동";

        foreach($_POST['pos'] as $id => $item){
            if($id == "undefined") {
                $ref = $item['ref'];
                $level = $item['level'];
                $uploaded['insertGetId'] = DB::table("jiny_pages_content")->insertGetId([
                    'enable' => 1,
                    'route' => $url['path'],
                    'type' => $item['type'],
                    'ref' => $ref,
                    'level' => $level,
                    'pos' => $item['pos'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
            } else {
                DB::table("jiny_pages_content")->where('id', $id)
                ->update([
                    'ref'=>$item['ref'],
                    'level'=>$item['level'],
                    'pos'=>$item['pos']
                ]);
            }
        }


        return response()->json($uploaded);
    }


    /**
     *  drag Section 이동
     *  변경된 section 순서를 반영합니다.
     */
    public function section(Request $requet)
    {
        $uploaded = [];
        $uploaded['message'] = "section 이동";
        $uploaded['post'] = $_POST;
        $url = parse_url($_POST['_uri']);


        foreach($_POST['pos'] as $id => $item){
            // widget 드래그, 신규 섹션 추가
            if($id == "undefined") {
                $ref = $item['ref'];
                $level = $item['level'];
                DB::table("jiny_pages_content")->insert([
                    'enable' => 1,
                    'route' => $url['path'],
                    'type' => $item['type'],
                    'ref' => $ref,
                    'level' => $level,
                    'pos' => $item['pos'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
            }
            // section pos 번호 변경
            else {
                DB::table("jiny_pages_content")->where('id', $id)
                ->update([
                    'pos' => $item['pos'],
                    'ref' => $item['ref'],
                    'level' => $item['level']
                ]);

                $this->level($id);
            }
        }

        return response()->json($uploaded);
    }

    private function level($id)
    {

        $rows = DB::table("jiny_pages_content")->where('ref', $id)->get();
        if($rows) {
            DB::table("jiny_pages_content")->where('ref', $id)->increment('level', 1);
            foreach($rows as $row) {
                $this->level($row->id);
            }
        }

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



    public function block_title()
    {
        return view("pages::blocks.title");
        //return "block code";
    }

    // 블럭을 html로 저장합니다.
    public function blocksave()
    {
        $uploaded = [];
        $uploaded['post'] = $_POST;

        // 경로 저장소 처리
        $url = parse_url($_POST['_uri']);
        $path = resource_path('pages').$url['path'];
        if (!is_dir($path)) mkdir($path, 755, true); // 디렉터리 생성

        $filename = $path."/".$_POST['id'].".html";
        file_put_contents($filename, $_POST['content']); // 파일저장

        // 정보갱신
        DB::table("jiny_pages_content")->where('id', $_POST['id'])
        ->update([
            'type'=>"html",
            'path' => $url['path']."/".$_POST['id'].".html"
        ]);

        return response()->json($uploaded);
    }


}
