<?php
namespace Jiny\Site\Page\API\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Upload404 extends Controller
{
    const UPLOAD = "pages";
    private $path;

    public function __construct()
    {

    }

    public function dropzone(Request $requet)
    {
        $uploaded = [];
        //$uploaded []= "uploaded";
        //return response()->json($uploaded);

        if (!empty($_FILES['file']['name'][0])) {
            // 여러개 파일 등록
            foreach ($_FILES['file']['name'] as $pos => $name) {
                $info = pathinfo($name);

                if($info['extension'] == "md") {
                    // markdown upload
                    $uploaded = $this->markdown($pos, $name);
                    $uploaded['info'] = $info;

                }
                else if($info['extension'] == "htm" || $info['extension'] == "html") {
                    $uploaded = $this->html($pos, $name);
                    $uploaded['info'] = $info;
                }
                else if($info['extension'] == "jpg" || $info['extension'] == "gif" || $info['extension'] == "png") {
                    $uploaded = $this->image($pos, $name);
                    $uploaded['info'] = $info;

                }
                // php blade 파일 업로드
                else if($info['extension'] == "php") {
                    $uploaded = $this->blade($pos, $name);
                    $uploaded['info'] = $info;

                }
            }
        }

        return response()->json($uploaded);
    }

    private function upload($pos, $name)
    {
        $uploaded = [];

        // 경로 저장소 처리
        $slot = $this->getSlot();
        $uploaded['slot'] = $slot;

        $path = resource_path("www");
        if($slot) {
            $path .= DIRECTORY_SEPARATOR.$slot;
        }

        $uri = parse_url($_POST['_uri'])['path'];
        $uploaded['uri'] = $uri;

        $path .= str_replace("/", DIRECTORY_SEPARATOR, $uri);
        $uploaded['path'] = $path;


        $filename = $path.DIRECTORY_SEPARATOR.$name;
        $uploaded['name'] = $name;
        $uploaded['file'] = $filename;

        $source = $_FILES['file']['tmp_name'][$pos];
        $uploaded['source'] = $source;

        if( move_uploaded_file($source, $filename) ){
            $uploaded['status'] = "upload success";
        }

        return $uploaded;
    }

    private function updateWidgets($element, $uploaded)
    {
        // json 파일 저장
        $file = $uploaded['path'].DIRECTORY_SEPARATOR."widgets.json";

        if(file_exists($file)) {
            $widgets = json_file_decode($file);
        } else {
            $widgets = [];
        }


        $widgets []= [
            'created_at' => date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s"),
                'enable'=>1,

                'route' => $uploaded['uri'],
                'path' => $uploaded['name'],

                'element' => $element,
                'pos'=>1,
                'ref'=>0,
                'level'=>1
        ];

        json_file_encode($file, $widgets);

        return $uploaded;
    }

    /**
     * markdown 파일 업로드
     */
    private function markdown($pos, $name)
    {
        $uploaded = $this->upload($pos, $name);
        $sectionId = $this->updateWidgets("widget-markdown", $uploaded);

        return $uploaded;
    }

    private function html($pos, $name)
    {
        $uploaded = $this->upload($pos, $name);
        $sectionId = $this->updateWidgets("widget-html", $uploaded);

        return $uploaded;
        /*
        // 경로 저장소 처리
        $url = parse_url($_POST['_uri']);
        $path = resource_path(self::UPLOAD).$url['path'];
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


        // DB 저장, 중복여부 체크
        $row = DB::table("jiny_route")->where('route',$url['path'])->get();
        if($row) {
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
        }

        // 업로드 컨덴츠 정보 등록



        // 업로드 컨덴츠 정보 등록
        $sectionId = DB::table("jiny_pages_content")->insertGetId([
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s"),
            'enable'=>1,
            'route'=>$url['path'],

            'element'=>"section",
            'pos'=>1,
            'ref'=>0,
            'level'=>1
        ]);

        $article = DB::table("jiny_pages_content")->insertGetId([
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s"),
            'enable'=>1,
            'route'=>$url['path'],
            'path'=>$url['path']."/".$name,
            'type'=>"html",
            'pos'=>1,
            'ref'=> $sectionId,
            'level'=>2
        ]);


        return $uploaded;
        */
    }

    private function image($pos, $name)
    {
        $uploaded = $this->upload($pos, $name);
        //$sectionId = $this->updateWidgets("widget-image", $uploaded);

        $element ="widget-image";
        // json 파일 저장
        $file = $uploaded['path'].DIRECTORY_SEPARATOR."widgets.json";

        if(file_exists($file)) {
            $widgets = json_file_decode($file);
        } else {
            $widgets = [];
        }

        $widgets []= [
            'created_at' => date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s"),
                'enable'=>1,

                'route' => $uploaded['uri'],
                'path' => [
                    $uploaded['name']
                ],
                'element' => $element,
                'pos'=>1,
                'ref'=>0,
                'level'=>1
        ];

        json_file_encode($file, $widgets);

        return $uploaded;
    }

    /**
     * Blade 파일 업로드
     */
    private function blade($pos, $name)
    {
        $uploaded = $this->upload($pos, $name);
        $sectionId = $this->updateWidgets("widget-blade", $uploaded);

        return $uploaded;

        // $uploaded = [];

        // // 경로 저장소 처리
        // $slot = $this->getSlot();
        // $uploaded['slot'] = $slot;

        // $path = resource_path("www");
        // if($slot) {
        //     $path .= DIRECTORY_SEPARATOR.$slot;
        // }

        // $uri = parse_url($_POST['_uri'])['path'];
        // $uploaded['uri'] = $uri;

        // $path .= str_replace("/", DIRECTORY_SEPARATOR, $uri);
        // $uploaded['path'] = $path;


        // $filename = $path.DIRECTORY_SEPARATOR.$name;
        // $uploaded['file'] = $filename;

        // $source = $_FILES['file']['tmp_name'][$pos];
        // $uploaded['source'] = $source;

        // if( move_uploaded_file($source, $filename) ){
        //     $uploaded['status'] = "upload success";
        // }

        // $result = DB::table('site_page_widgets')
        //     ->where('route',$uri)
        //     ->where('path',$name)
        //     ->first();

        // // 업로드 컨덴츠 정보 등록
        // if(!$result) {
        //     $sectionId = DB::table('site_page_widgets')->insertGetId([
        //         'created_at' => date("Y-m-d H:i:s"),
        //         'updated_at'=>date("Y-m-d H:i:s"),
        //         'enable'=>1,

        //         'route' => $uri,
        //         'path' => $name,
        //         'element' => "widget-blade",
        //         'pos'=>1,
        //         'ref'=>0,
        //         'level'=>1
        //     ]);
        // }





        /*

        .$url['path'];
        $path = str_replace("/",DIRECTORY_SEPARATOR,$path);
        if (!is_dir($path)) mkdir($path, 755, true);

        $filename = $path.DIRECTORY_SEPARATOR.$name;

        if( move_uploaded_file($source, $filename) ){
            $uploaded []= [
                'name' => $name,
                'url' => parse_url($_POST['_uri']),
                'path' => $path
            ];

            $uploaded['file']= $filename;
        }


        // DB 저장, 중복여부 체크
        $row = DB::table("jiny_route")->where('route',$url['path'])->get();
        if($row) {
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
        }






        $article = DB::table("jiny_pages_content")->insertGetId([
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s"),
            'enable'=>1,
            'route'=>$url['path'],
            'path'=>$url['path']."/".$name,
            'type'=>"blade",
            'pos'=>1,
            'ref'=> $sectionId,
            'level'=>2
        ]);
        */

        return $uploaded;
    }


    private function getSlot()
    {
        // 2.slot
        // 여기에 인증된 사용자에 대한 처리를 추가합니다.
        $user = Auth::user();
        $slots = config("jiny.site.userslot");
        if($user && count($slots)>0){
            if($slots && isset($slots[$user->id])) {
                return $slots[$user->id];
            }
        }

        // 설정파일에서 active slot을 읽어옴
        else {
            $slots = config("jiny.site.slot");
            if(is_array($slots) && count($slots)>0) {
                foreach($slots as $slot => $item) {
                    if($item['active']) {
                        return  $slot;
                    }
                }
            }
        }

        return false;
    }



}
