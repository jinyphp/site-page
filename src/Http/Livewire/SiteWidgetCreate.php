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
 * 새로운 위젯을 추가하기 위한 팝업을 관리합니다.
 */
class SiteWidgetCreate extends Component
{
    public $uri;
    public $actions=[];
    public $action_path;

    public $widgets=[];

    public $pos;
    public $popupForm = false;
    public $popupWindowWidth = "4xl";

    public $widgetList = [];
    public $template_type = "";

    use \Jiny\Widgets\Http\Trait\DesignMode;

    public function mount()
    {
        $this->actions = $this->loadActions();

        if(isset($this->actions['widgets'])) {
            $this->widgets = $this->actions['widgets'];
        } else {
            // 새로운 페이지 생성시, 초기값 설정 필요
            $this->widgets = [];
        }
    }

    private function loadActions()
    {
        if(!$this->uri) {
            $this->uri = "/".Request::path();
        }

        $path = resource_path('actions');

        // actions 파일 경로 체크
        $this->action_path = $path.DIRECTORY_SEPARATOR;
        $this->action_path .= str_replace('/', DIRECTORY_SEPARATOR, $this->uri);
        $this->action_path .= ".json";


        $path .= str_replace('/', DIRECTORY_SEPARATOR, $this->uri);
        $path .= ".json";

        if(file_exists($path)) {
            $actions = json_file_decode($path);
        } else {
            $actions = [];
        }

        return $actions;
    }

    /**
     * 팝업 폼 화면
     */
    public function render()
    {
        $viewFile = "jiny-site-page::pages.widget_create";
        return view($viewFile,[

        ]);
    }

    // 초기화
    public function updatedTemplateType()
    {
        if($this->template_type == "선택하세요") {
            $this->template_type = null;
        }
    }

    #[On('siteWidgetCreate')]
    public function create($pos=null)
    {
        $this->popupForm = true;
        $this->pos = $pos;

        // 위젯목록을 읽어 옵니다.
        $this->widgetList = widgetList();
    }

    /**
     * 팝업 폼 닫기
     */
    public function cancel()
    {
        $this->popupForm = false;
    }

    /**
     * 위젯 생성
     */
    public function store($type=null)
    {
        $this->popupForm = false;
        $this->widgetList = [];

        if($type == "markdown") {
            $this->markdown();
        } else if($type == "blade") {
            $this->blade();
        } else if($type == "html") {
            $this->html();
        } else if($type == "image") {
            $this->image();
        } else {
            // 템플릿 위젯
            $this->template($type);
        }

    }

    /**
     * 위젯 저장
     */
    private function saveWidgets($widget)
    {
        // pos 순서에 맞게 위젯 추가
        $temp = [];
        $i = 0;
        if(count($this->widgets) > 0) {
            foreach($this->widgets as $item) {
                // 이전에 추가
                if($i == $this->pos) {
                    $temp []= $widget;
                }

                $temp []= $item;
                $i++;
            }
            // 마지막에 추가
            if($i == $this->pos) {
                $temp []= $widget;
            }
        } else {
            $temp []= $widget;
        }

        //dd($temp);

        return $temp;
    }

    // /**
    //  * 위젯을 삭제합니다.
    //  */
    // public function remove($key)
    // {
    //     for($i=0; $i<count($this->widgets); $i++) {
    //         if($this->widgets[$i]['key'] == $key) {
    //             break;
    //         }
    //     }

    //     // $i 번째 위젯 삭제
    //     $slot = www_slot();
    //     $filePath = resource_path('www/'.$slot);
    //     $filename = $filePath.$filename.$this->widgets[$i]['path'];
    //     dd($filename);

    //     //unlink();

    //     //unset($this->widgets[$i]);

    //     // unset($this->actions['widgets'][$i]);
    //     // json_file_encode($this->action_path, $this->actions); // 다시저장

    //     // 페이지 리로드 이벤트 발생
    //     // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
    //     $this->dispatch('page-realod');
    // }

    // private function tempFile()
    // {
    //     $count = count($this->widgets);

    //     $key = hash('sha256', uniqid(mt_rand().$count, true));
    //     $key = str_replace('.', '', $key); // 소수점(.)을 빈 문자열로 대체하여 제거
    //     $key = substr($key, 0, 10);
    //     $filename = $this->uri.'/'.$key;//.".json";

    //     return $filename;
    // }

    /**
     * 마크다운 위젯 생성
     * site-widget-markdown
     */
    public function markdown()
    {
        $count = count($this->widgets);

        $key = hash('sha256', uniqid(mt_rand().$count, true));
        $key = str_replace('.', '', $key); // 소수점(.)을 빈 문자열로 대체하여 제거
        $key = substr($key, 0, 10);
        $filename = $this->uri.'/'.$key;//.".json";

        $widget = [
            'filename' => $filename,
            'element' => 'site-widget-markdown',
            'name' => 'markdown',
            'key' => $key,
            'route' => $this->uri,
            'path' => $key.".md",
            'view' => [
                'list' => 'jiny-widgets::markdown.list',
                'form' => 'jiny-widgets::markdown.form'
            ],
            'pos'=>$this->pos,
            'ref'=>0,
            'level'=>1
        ];

        // 위젯 추가 및 action 저장
        $this->widgets = $this->saveWidgets($widget);
        $this->actions['widgets'] = $this->widgets;
        json_file_encode($this->action_path, $this->actions); // 저장

        // 파일생성
        $slot = www_slot();
        $filePath = resource_path('www/'.$slot);
        if(!is_dir($filePath.$this->uri)) {
            mkdir($filePath.$this->uri, 0777, true);
        }

        $filename = $filePath.$filename.".md";
        file_put_contents($filename, '마크다운 컨덴츠를 작성해 주세요!');

        // 팝업 닫기
        $this->popupForm = false;

        // 페이지 리로드 이벤트 발생
        // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
        $this->dispatch('page-realod');
    }



    /**
     * HTML 위젯 생성
     * site-widget-html
     */
    public function html()
    {
        $count = count($this->widgets);

        $key = hash('sha256', uniqid(mt_rand().$count, true));
        $key = str_replace('.', '', $key); // 소수점(.)을 빈 문자열로 대체하여 제거
        $key = substr($key, 0, 10);
        $filename = $this->uri.'/'.$key;//.".json";

        $widget = [
            'filename' => $filename,
            'element' => 'site-widget-html',
            'name' => 'html',
            'key' => $key,
            'route' => $this->uri,
            'path' => $key.".html",
            'view' => [
                'list' => 'jiny-widgets::html.list',
                'form' => 'jiny-widgets::html.form'
            ],
            'pos'=>$this->pos,
            'ref'=>0,
            'level'=>1
        ];

        // 위젯 추가 및 action 저장
        $this->widgets = $this->saveWidgets($widget);
        $this->actions['widgets'] = $this->widgets;
        json_file_encode($this->action_path, $this->actions); // 저장

        // 파일생성
        $slot = www_slot();
        $filePath = resource_path('www/'.$slot);
        if(!is_dir($filePath.$this->uri)) {
            mkdir($filePath.$this->uri, 0777, true);
        }

        $filename = $filePath.$filename.".html";
        file_put_contents($filename, 'html 컨덴츠를 작성해 주세요!');


        // 팝업 닫기
        $this->popupForm = false;

        // 페이지 리로드 이벤트 발생
        // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
        $this->dispatch('page-realod');
    }


    /**
     * 이미지 위젯 생성
     * site-widget-image
     */
    public function image()
    {
        $count = count($this->widgets);

        $key = hash('sha256', uniqid(mt_rand().$count, true));
        $key = str_replace('.', '', $key); // 소수점(.)을 빈 문자열로 대체하여 제거
        $key = substr($key, 0, 10);
        $filename = $this->uri.'/'.$key;//.".json";

        $widget = [
            'filename' => $filename,
            'element' => 'site-widget-image',
            'name' => 'image',
            'key' => $key,
            'route' => $this->uri,
            'path' => [],
            'view' => [
                'list' => 'jiny-site-page::widgets.image.list',
                'form' => 'jiny-site-page::widgets.image.form'
            ],
            'pos'=>$this->pos,
            'ref'=>0,
            'level'=>1
        ];

        // 위젯 추가 및 action 저장
        //dd($widget);
        $this->widgets = $this->saveWidgets($widget);
        //dump($this->widgets);
        $this->actions['widgets'] = $this->widgets;
        //dd($this->actions);
        json_file_encode($this->action_path, $this->actions); // 저장



        // 팝업 닫기
        $this->popupForm = false;

        // 페이지 리로드 이벤트 발생
        // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
        $this->dispatch('page-realod');
    }

    /**
     * Blade 위젯 생성
     * site-widget-blade
     */
    public function blade()
    {
        $count = count($this->widgets);

        $key = hash('sha256', uniqid(mt_rand().$count, true));
        $key = str_replace('.', '', $key); // 소수점(.)을 빈 문자열로 대체하여 제거
        $key = substr($key, 0, 10);
        $filename = $this->uri.'/'.$key;//.".json";

        $widget = [
            'filename' => $filename,
            'element' => 'site-widget-blade',
            'name' => 'blade',
            'key' => $key,
            'route' => $this->uri,
            'path' => $key.".blade.php",
            'view' => [
                'list' => 'jiny-widgets::blade.list',
                'form' => 'jiny-widgets::blade.form'
            ],
            'pos'=>$this->pos,
            'ref'=>0,
            'level'=>1
        ];

        // 위젯 추가 및 action 저장
        $this->widgets = $this->saveWidgets($widget);
        $this->actions['widgets'] = $this->widgets;
        json_file_encode($this->action_path, $this->actions); // 저장

        // 파일생성
        $slot = www_slot();
        $filePath = resource_path('www/'.$slot);
        if(!is_dir($filePath.$this->uri)) {
            mkdir($filePath.$this->uri, 0777, true);
        }

        $filename = $filePath.$filename.".blade.php";
        file_put_contents($filename, 'blade 템플릿을 작성해 주세요!');

        // 팝업 닫기
        $this->popupForm = false;

        // 페이지 리로드 이벤트 발생
        // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
        $this->dispatch('page-realod');
    }

    /**
     * 템플릿 위젯 생성
     */
    public function template($type)
    {
        $count = count($this->widgets);

        $key = hash('sha256', uniqid(mt_rand().$count, true));
        $key = str_replace('.', '', $key); // 소수점(.)을 빈 문자열로 대체하여 제거
        $key = substr($key, 0, 10);
        $filename = $this->uri.'/'.$key;//.".json";

        $row = DB::table('site_widgets')
            ->where('name', $type)
            ->first();
        if($row) {
            $widget = [
                'filename' => $filename,
                'element' => 'site-widget',
                'name' => $type,
                'key' => $key,
                'route' => $this->uri,
                //'path' => $key.".md",
                'view' => [
                    'list' => $row->view_list,//'jiny-widgets::widget.list',
                    'form' => $row->view_form//'jiny-widgets::widget.form'
                ],
                'pos'=>$this->pos,
                'ref'=>0,
                'level'=>1
            ];

            // 위젯 추가 및 action 저장
            $this->widgets = $this->saveWidgets($widget);
            $this->actions['widgets'] = $this->widgets;
            json_file_encode($this->action_path, $this->actions); // 저장

        }

        // 팝업 닫기
        $this->popupForm = false;

        // 페이지 리로드 이벤트 발생
        // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
        $this->dispatch('page-realod');
    }

}
