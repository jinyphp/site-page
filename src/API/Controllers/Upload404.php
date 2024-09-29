<?php
namespace Jiny\Site\Page\API\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * 페이지 drag upload
 */
class Upload404 extends Controller
{
    //const UPLOAD = "pages";
    private $path;

    public function __construct()
    {

    }

    public function dropzone(Request $requet)
    {
        $uploaded = [];

        if (!empty($_FILES['file']['name'][0])) {

            // 여러개 파일 등록
            foreach ($_FILES['file']['name'] as $pos => $name) {
                $info = pathinfo($name);

                ## 마크다운 파일
                if($info['extension'] == "md") {
                    // markdown upload
                    $uploaded = $this->markdown($pos, $name);
                    $uploaded['info'] = $info;

                }
                ## html, html 파일
                else if($info['extension'] == "htm" || $info['extension'] == "html") {
                    $uploaded = $this->html($pos, $name);
                    $uploaded['info'] = $info;
                }
                ## 이미지 파일
                else if($info['extension'] == "jpg"
                    || $info['extension'] == "gif"
                    || $info['extension'] == "png"
                    || $info['extension'] == "svg") {
                    $uploaded = $this->image($pos, $name);
                    $uploaded['info'] = $info;

                }
                ## php blade 파일 업로드
                else if($info['extension'] == "php") {
                    $uploaded = $this->blade($pos, $name);
                    $uploaded['info'] = $info;

                }
            }

        }

        return response()->json($uploaded);
    }

    /**
     * 공용, 파일 업드로 메소드
     */
    private function upload($pos, $name)
    {
        $uploaded = [];

        // 슬록 확인
        $slot = $this->getSlot();
        $uploaded['slot'] = $slot;

        // 업로드 경로 확인
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

        // 업로드된 temp 파일을 실제 파일 경로로 이동
        if( move_uploaded_file($source, $filename) ){
            $uploaded['status'] = "upload success";
        }

        return $uploaded;
    }


    private function updateWidgets($element, $uploaded)
    {
        // Actions 파일에 widget 데이터를 삽입
        $path = resource_path("actions");
        $file = $path.str_replace('/',DIRECTORY_SEPARATOR, $uploaded['uri']);
        if(!is_dir($file)) {
            // 디렉터리 생성
            mkdir($file,777,true);
        }

        $file .= ".json";
        $uploaded['actions'] = $file;
        if(file_exists($file)) {
            $actions = json_file_decode($file);
        } else {
            $actions = [];
        }

        if(!isset($actions['widgets'])) {
            $actions['widgets'] = [];
        }

        //$actions['file'] = $file;
        //return $actions;

        $key = uniqid(mt_rand().$element, true);
        $key = str_replace('.', '', $key); // 소수점(.)을 빈 문자열로 대체하여 제거

        $actions['widgets'] []= [
            'created_at' => date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s"),
                'enable'=>1,
                'key' => $key,

                'route' => $uploaded['uri'],
                'path' => $uploaded['name'],

                'element' => $element,
                'pos'=>1,
                'ref'=>0,
                'level'=>1
        ];

        // json 데이터를 파일로 저장
        json_file_encode($file, $actions);

        return $uploaded;
    }



    /**
     * 슬롯 리소스 위치에 json 파일 수정
     */
    private function updateJsonSlot()
    {
        // json 파일 저장
        $file = $uploaded['path'].DIRECTORY_SEPARATOR."widgets.json";

        if(file_exists($file)) {
            $widgets = json_file_decode($file);
        } else {
            $widgets = [];
        }


        $key = uniqid(mt_rand().$element, true);
        $key = str_replace('.', '', $key); // 소수점(.)을 빈 문자열로 대체하여 제거

        $widgets []= [
            'created_at' => date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s"),
                'enable'=>1,
                'key' => $key,

                'route' => $uploaded['uri'],
                'path' => $uploaded['name'],

                'element' => $element,
                'pos'=>1,
                'ref'=>0,
                'level'=>1
        ];

        json_file_encode($file, $widgets);
    }

    /**
     * markdown 파일 업로드
     */
    private function markdown($pos, $name)
    {
        $uploaded = $this->upload($pos, $name);

        $element ="widget-markdown";
        $uploaded = $this->updateWidgets($element, $uploaded);

        return $uploaded;
    }

    private function html($pos, $name)
    {
        $uploaded = $this->upload($pos, $name);

        $element = "widget-html";
        $uploaded = $this->updateWidgets($element, $uploaded);

        return $uploaded;

    }

    private function image($pos, $name)
    {
        $uploaded = $this->upload($pos, $name);

        $element ="widget-image";
        $uploaded['name'] = [ $uploaded['name'] ];
        $uploaded = $this->updateWidgets($element, $uploaded);

        /*
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
        */

        return $uploaded;
    }

    /**
     * Blade 파일 업로드
     */
    private function blade($pos, $name)
    {
        $uploaded = $this->upload($pos, $name);

        $element ="widget-blade";
        $sectionId = $this->updateWidgets($element, $uploaded);

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
