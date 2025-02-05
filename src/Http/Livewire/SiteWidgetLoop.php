<?php
namespace Jiny\Site\Page\Http\Livewire;

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

/**
 * Page Widget Loop
 */
class SiteWidgetLoop extends Component
{
    public $actions;
    public $action_path;

    public $uri;
    public $mode;
    public $widgets;

    public $popupForm = false;
    public $popupWindowWidth = "4xl";
    public $pos;

    public $widgetList = [];

    public $template_type = "";


    use \Jiny\Widgets\Http\Trait\DesignMode;

    public function mount()
    {
        Action();

        // 1. 엑션정보 읽기
        if(!isset($this->actions['widgets'])) {
            $this->actions = $this->loadActions();
        }

        // 2. widgets 정보읽기
        if(isset($this->actions['widgets'])) {
            $this->widgets = $this->actions['widgets'];
        } else {
            $this->actions['widgets'] = [];
            $this->widgets = [];
        }

    }

    private function loadActions()
    {
        if(!$this->uri) {
            $this->uri = "/".Request::path();
        }

        $path = resource_path('actions');
        //$slot = www_slot();

        // actions 파일 경로 체크
        $this->action_path = $path.DIRECTORY_SEPARATOR;
        $this->action_path .= str_replace('/', DIRECTORY_SEPARATOR, $this->uri);
        $this->action_path .= ".json";


        //$path .= DIRECTORY_SEPARATOR.$slot;
        $path .= str_replace('/', DIRECTORY_SEPARATOR, $this->uri);
        //$path .= DIRECTORY_SEPARATOR."widgets.json";
        $path .= ".json";

        if(file_exists($path)) {
            $actions = json_file_decode($path);
        } else {
            $actions = [];
        }

        return $actions;


    }


    public function render()
    {
        if($this->design) {
            $viewFile = "jiny-site-page::pages.design";
        } else {
            $viewFile = "jiny-site-page::pages.loop";
        }

        $viewFile = "jiny-site-page::pages.design";
        return view($viewFile,[
        ]);
    }


    // // 초기화
    // public function updatedTemplateType()
    // {
    //     if($this->template_type == "선택하세요") {
    //         $this->template_type = null;
    //     }
    // }





    // public function cancel()
    // {
    //     $this->popupForm = false;
    // }

    // public function create($pos=null)
    // {
    //     $this->popupForm = true;
    //     $this->pos = $pos;

    //     // 위젯목록을 읽어 옵니다.
    //     $this->widgetList = widgetList();
    //     //dd($this->widgetList);
    // }

    // public function store($type=null)
    // {
    //     $this->popupForm = false;
    //     $this->widgetList = [];

    //     if($type == "markdown") {
    //         $this->markdown();
    //     } else if($type == "blade") {
    //         $this->blade();
    //     } else if($type == "html") {
    //         $this->html();
    //     } else if($type == "image") {
    //         $this->image();
    //     } else {
    //         // 템플릿 위젯
    //         $this->template($type);
    //     }

    // }



    // /**
    //  * 위젯을 삭제합니다.
    //  */
    // public function remove($id)
    // {
    //     foreach($this->actions['widgets'] as $i => $item) {
    //         if($item['key'] == $id) {
    //             break;
    //         }
    //     }

    //     unset($this->actions['widgets'][$i]);
    //     json_file_encode($this->action_path, $this->actions); // 다시저장

    //     // 페이지 리로드 이벤트 발생
    //     // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
    //     $this->dispatch('page-realod');
    // }

    public function widgetSetLayout($key)
    {
        $this->dispatch('widget-layout-setting',$key);
    }


    private function updateActionJson($element, $ext)
    {
        if(isset($this->actions['widgets'])) {
            $src = $this->actions['widgets'];
        } else {
            $src = [];
        }

        $widgets = [];
        for($i=0; $i<$this->pos; $i++) {
            $widgets []= $src[$i];
        }

        $key = uniqid(mt_rand().$i, true);
        $key = str_replace('.', '', $key); // 소수점(.)을 빈 문자열로 대체하여 제거

        $widgets []= [
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s"),
            'enable'=>1,
            'key' => $key,
            'route' => $this->uri,
            'path' => $i.$ext,

            'element' => $element,
            'pos'=>1,
            'ref'=>0,
            'level'=>1
        ];

        for(; $i<count($src); $i++) {
            $widgets []= $src[$i];
        }

        $this->actions['widgets'] = $widgets;
        json_file_encode($this->action_path, $this->actions);
    }

    // public function template($type)
    // {
    //     $i = count($this->actions['widgets']);
    //     $key = hash('sha256', uniqid(mt_rand().$i, true));
    //     $key = str_replace('.', '', $key); // 소수점(.)을 빈 문자열로 대체하여 제거
    //     $key = substr($key, 0, 10);
    //     $filename = $this->uri.'/'.$key;//.".json";

    //     $row = DB::table('site_widgets')
    //         ->where('name', $type)
    //         ->first();

    //     $widget = [
    //         'filename' => $filename,
    //         'element' => 'site-widget',
    //         'name' => $type,
    //         'key' => $key,
    //         'route' => $this->uri,
    //         //'path' => $key.".md",
    //         'view' => [
    //             'list' => $row->view_list,//'jiny-widgets::widget.list',
    //             'form' => $row->view_form//'jiny-widgets::widget.form'
    //         ],
    //         'pos'=>$i,
    //         'ref'=>0,
    //         'level'=>1
    //     ];

    //     $this->actions['widgets'] []= $widget; // 위젯 추가
    //     json_file_encode($this->action_path, $this->actions); // 저장

    //     // 페이지 리로드 이벤트 발생
    //     // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
    //     $this->dispatch('page-realod');
    // }

    // /**
    //  * 마크다운 위젯 생성
    //  * site-widget-markdown
    //  */
    // public function markdown()
    // {
    //     $i = count($this->actions['widgets']);
    //     $key = hash('sha256', uniqid(mt_rand().$i, true));
    //     $key = str_replace('.', '', $key); // 소수점(.)을 빈 문자열로 대체하여 제거
    //     $key = substr($key, 0, 10);
    //     $filename = $this->uri.'/'.$key;//.".json";

    //     $widget = [
    //         'filename' => $filename,
    //         'element' => 'site-widget-markdown',
    //         'name' => 'markdown',
    //         'key' => $key,
    //         'route' => $this->uri,
    //         'path' => $key.".md",
    //         'view' => [
    //             'list' => 'jiny-widgets::markdown.list',
    //             'form' => 'jiny-widgets::markdown.form'
    //         ],
    //         'pos'=>$i,
    //         'ref'=>0,
    //         'level'=>1
    //     ];

    //     $this->actions['widgets'] []= $widget; // 위젯 추가
    //     json_file_encode($this->action_path, $this->actions); // 저장

    //     $this->popupForm = false;

    //     // 페이지 리로드 이벤트 발생
    //     // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
    //     $this->dispatch('page-realod');
    // }


    // /**
    //  * Blade 위젯 생성
    //  * site-widget-blade
    //  */
    // public function blade()
    // {
    //     $i = count($this->actions['widgets']);
    //     $key = hash('sha256', uniqid(mt_rand().$i, true));
    //     $key = str_replace('.', '', $key); // 소수점(.)을 빈 문자열로 대체하여 제거
    //     $key = substr($key, 0, 10);
    //     $filename = $this->uri.'/'.$key;//.".json";

    //     $widget = [
    //         'filename' => $filename,
    //         'element' => 'site-widget-blade',
    //         'name' => 'blade',
    //         'key' => $key,
    //         'route' => $this->uri,
    //         'path' => $key.".blade.php",
    //         'view' => [
    //             'list' => 'jiny-widgets::blade.list',
    //             'form' => 'jiny-widgets::blade.form'
    //         ],
    //         'pos'=>$i,
    //         'ref'=>0,
    //         'level'=>1
    //     ];

    //     $this->actions['widgets'] []= $widget; // 위젯 추가
    //     json_file_encode($this->action_path, $this->actions); // 저장


    //     // $element ="widget-blade";
    //     // $ext = ".blade.php";
    //     // $this->updateActionJson($element, $ext);

    //     $this->popupForm = false;

    //     // 페이지 리로드 이벤트 발생
    //     // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
    //     $this->dispatch('page-realod');
    // }

    // /**
    //  * HTML 위젯 생성
    //  * site-widget-html
    //  */
    // public function html()
    // {
    //     $i = count($this->actions['widgets']);
    //     $key = hash('sha256', uniqid(mt_rand().$i, true));
    //     $key = str_replace('.', '', $key); // 소수점(.)을 빈 문자열로 대체하여 제거
    //     $key = substr($key, 0, 10);
    //     $filename = $this->uri.'/'.$key;//.".json";

    //     $widget = [
    //         'filename' => $filename,
    //         'element' => 'site-widget-html',
    //         'name' => 'html',
    //         'key' => $key,
    //         'route' => $this->uri,
    //         'path' => $key.".html",
    //         'view' => [
    //             'list' => 'jiny-widgets::html.list',
    //             'form' => 'jiny-widgets::html.form'
    //         ],
    //         'pos'=>$i,
    //         'ref'=>0,
    //         'level'=>1
    //     ];

    //     $this->actions['widgets'] []= $widget; // 위젯 추가
    //     json_file_encode($this->action_path, $this->actions); // 저장

    //     // $element ="widget-html";
    //     // $ext = ".html";
    //     // $this->updateActionJson($element, $ext);

    //     $this->popupForm = false;

    //     // 페이지 리로드 이벤트 발생
    //     // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
    //     $this->dispatch('page-realod');
    // }

    // /**
    //  * 이미지 위젯 생성
    //  * site-widget-image
    //  */
    // public function image()
    // {
    //     $i = count($this->actions['widgets']);
    //     $key = hash('sha256', uniqid(mt_rand().$i, true));
    //     $key = str_replace('.', '', $key); // 소수점(.)을 빈 문자열로 대체하여 제거
    //     $key = substr($key, 0, 10);
    //     $filename = $this->uri.'/'.$key;//.".json";

    //     $widget = [
    //         'filename' => $filename,
    //         'element' => 'site-widget-image',
    //         'name' => 'image',
    //         'key' => $key,
    //         'route' => $this->uri,
    //         'path' => [],
    //         'view' => [
    //             'list' => 'jiny-site-page::widgets.image.list',
    //             'form' => 'jiny-site-page::widgets.image.form'
    //         ],
    //         'pos'=>$i,
    //         'ref'=>0,
    //         'level'=>1
    //     ];

    //     $this->actions['widgets'] []= $widget; // 위젯 추가
    //     json_file_encode($this->action_path, $this->actions); // 저장

    //     $this->popupForm = false;

    //     // if(isset($this->actions['widgets'])) {
    //     //     $src = $this->actions['widgets'];
    //     // } else {
    //     //     $src = [];
    //     // }

    //     // $element ="widget-image";
    //     // // $ext = ".html";
    //     // // $this->updateActionJson($element, $ext);
    //     // $widgets = [];
    //     // for($i=0; $i<$this->pos; $i++) {
    //     //     $widgets []= $src[$i];
    //     // }

    //     // $key = uniqid(mt_rand().$i, true);
    //     // $key = str_replace('.', '', $key); // 소수점(.)을 빈 문자열로 대체하여 제거

    //     // $widgets []= [
    //     //     'created_at' => date("Y-m-d H:i:s"),
    //     //     'updated_at'=>date("Y-m-d H:i:s"),
    //     //     'enable'=>1,
    //     //     'key' => $key,
    //     //     'route' => $this->uri,
    //     //     'path' => [

    //     //     ],
    //     //     'element' => $element,
    //     //     'pos'=>1,
    //     //     'ref'=>0,
    //     //     'level'=>1
    //     // ];

    //     // for(; $i<count($src); $i++) {
    //     //     $widgets []= $src[$i];
    //     // }

    //     // //dd($widgets);
    //     // $this->actions['widgets'] = $widgets;
    //     // json_file_encode($this->action_path, $this->actions);


    //     // 페이지 리로드 이벤트 발생
    //     // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
    //     $this->dispatch('page-realod');
    // }

    #[On('widget-reload')]
    public function widgetReload()
    {
        // dropzone 에서 호출
    }

    /**
     * 페이지 편집
     */
    // public $editMode=false;
    // public $forms=[];
    // public $message;
    // public $deletePopup = false;
    // public $deleteConfirm = false;
    // public function pageEdit()
    // {
    //     $this->editMode = true;
    //     $this->forms['uri'] = $this->uri;
    //     $this->message = null;
    // }

    // public function pageEditUpdate()
    // {
    //     $path1 = resource_path("www");
    //     $path1 .= DIRECTORY_SEPARATOR.www_slot();
    //     $file = str_replace("/",DIRECTORY_SEPARATOR,$this->uri);

    //     $dst = str_replace("/",DIRECTORY_SEPARATOR,$this->forms['uri']);

    //     if(file_exists($path1.$dst)) {
    //         $this->message = "존재하는 uri 입니다.";
    //         return;
    //     }
    //     //dump($path1.$file);
    //     //dd($path1.$dst);
    //     ## 1. slot 리소스 변경
    //     $temp = substr($dst, 0, strrpos($dst, '/'));
    //     if(!file_exists($path1.$temp)) {
    //          mkdir($path1.$temp, 0777, true);
    //     }
    //     rename($path1.$file, $path1.$dst);

    //     ## 2. actions 파일 변경
    //     $path2 = resource_path("actions");
    //     if(!file_exists($path2.$temp)) {
    //         mkdir($path2.$temp, 0777, true);
    //     }
    //     rename($path2.$file.".json", $path2.$dst.".json");

    //     //dd($this->forms);
    //     $this->editMode = false;
    //     $this->forms = [];
    //     $this->message = null;

    //     // 페이지 리로드 이벤트 발생
    //     // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
    //     $this->dispatch('page-realod');
    // }

    // public function pageEditCancel()
    // {
    //     $this->editMode = false;

    //     $this->deletePopup = false;
    //     $this->deleteConfirm = false;
    // }

    // public function pageEditDelete()
    // {
    //     $this->deletePopup = true;
    // }

    // public function pageEditDeleteConfirm()
    // {
    //     // 1.action 설정값을 삭제합니다.
    //     $path2 = resource_path("actions");
    //     $file = str_replace("/",DIRECTORY_SEPARATOR,$this->uri);
    //     if(file_exists($path2.$file.".json")) {
    //         unlink($path2.$file.".json");

    //         if(is_dir($path2.$file)) {
    //             rmdir($path2.$file);
    //         }
    //     }


    //     // 2. slot 리소스 파일을 삭제합니다.
    //     $path1 = resource_path("www");
    //     $path1 .= DIRECTORY_SEPARATOR.www_slot();
    //     $file = str_replace("/",DIRECTORY_SEPARATOR,$this->uri);
    //     if(file_exists($path1.$file)) {
    //         $dir = scandir($path1.$file);
    //         //dd($dir);
    //         // 부속파일 삭제
    //         foreach($dir as $item) {
    //             if($item == "." || $item == "..") continue;
    //             unlink($path1.$file.DIRECTORY_SEPARATOR.$item);
    //         }

    //         if(is_dir($path1.$file)) {
    //             rmdir($path1.$file);
    //         }
    //      }

    //     $this->editMode = false;

    //     $this->deletePopup = false;
    //     $this->deleteConfirm = false;

    //     // 페이지 리로드 이벤트 발생
    //     // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
    //     $this->dispatch('page-realod');
    // }


    /**
     * HotKey: page edit
     */
    #[On('page-mode')]
    public function actionMode($mode=null)
    {
        if($this->design) {
            $this->design = false;
            $this->popupForms = false;

            $this->editMode = false;
        } else {
            $this->design = "page";
            $this->popupForms = true;

            $this->editMode = true;
            $this->forms['uri'] = $this->uri;
        }

        $this->message = null;
    }

}
