<?php
namespace Jiny\Site\Page\Http\Livewire;

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

use Livewire\WithFileUploads;
use Livewire\Attributes\On;

/**
 * 이미지 위젯
 */
class WidgetImage extends Component
{
    public $actions = [];
    public $action_path;

    use WithFileUploads;
    use \Jiny\WireTable\Http\Trait\Upload;


    public $uri;
    public $slot;
    public $path;
    public $file;

    public $widget = [];
    public $widget_id;
    //public $pages = [];
    //public $content = "hello";

    public $images;

    public $message;

    public $mode;
    public $editable = false;
    public $_id;
    public $forms=[];


    use \Jiny\Widgets\Http\Trait\DesignMode;

    public function mount()
    {
        $this->uri = Request::path();
        $this->slot = $this->getSlot();

        if(isset($this->widget['path'])) {
            $path = resource_path('www');

            if($this->slot) {
                $path = $path.DIRECTORY_SEPARATOR.$this->slot;
                $path .= DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $this->uri);
            }

            //$this->file = $path.DIRECTORY_SEPARATOR.$this->widget['path'];
            $this->path = $path; //.DIRECTORY_SEPARATOR.$this->widget['path'];
            if(!is_dir($this->path)) {
                mkdir($this->path, 0777, true);
            }
        }



        // actions 파일 경로 체크
        $this->action_path = resource_path('actions');
        $this->action_path .= DIRECTORY_SEPARATOR;
        $this->action_path .= str_replace('/', DIRECTORY_SEPARATOR, $this->uri);
        $this->action_path .= ".json";

        // actions 데이터 읽기
        $this->actions = $this->loadActions();


        // $this->image = "/".$this->uri."/".$this->widget['path'];
        // 이미지 경로 다시 생성
        $this->images = [];
        if(isset($this->widget['files'])) {
            foreach($this->widget['files'] as $i => $item) {
                $this->images[$i] = "/".$this->uri."/".$item;
            }
        }

        $this->widget['view']['design'] = "jiny-site-page::widgets.image.design";

    }

    private function loadActions()
    {
        if(file_exists($this->action_path)) {
            $actions = json_file_decode($this->action_path);
        } else {
            $actions = [];
        }

        return $actions;
    }



    public function render()
    {
        $viewFile = "jiny-site-page::widgets.image.layout";
        return view($viewFile);
    }


    public function create()
    {
        $this->editable = true;
        $this->_id = null;
    }

    public function store()
    {
        $this->editable = false;

        if(isset($this->forms['image'])) {
            // 3. 파일 업로드 체크 Trait
            $this->upload_path = "/"; //"/upload";
            $this->upload_move = $this->uri; // "/images/widgets/".$this->uri; // 슬롯 안쪽으로 이동
            $this->fileUpload($this->forms, $this->upload_path);

            if(!isset($this->widget['files'])) {
                $this->widget['files'] = [];
            }
            $this->widget['files'][] = basename($this->forms['image']);

            $id = $this->widget_id;
            $this->actions['widgets'][$id] = $this->widget;
            json_file_encode($this->action_path, $this->actions);

            // 이미지 경로 다시 생성
            $this->images = [];
            if(isset($this->widget['files'])) {
                foreach($this->widget['files'] as $i => $item) {
                    $this->images[$i] = "/".$this->uri."/".$item;
                }
            }
        }

        $this->forms = [];
        return true;
    }

    public function edit($i)
    {
        $this->editable = true;
        $this->_id = $i;
        $this->forms['image'] = $this->widget['files'][$i];
    }

    public function cencel()
    {
        $this->mode = null;
        $this->editable = false;
        $this->forms = [];
    }

    public function update()
    {
        //$this->mode = null;
        $this->editable = false;

        if(isset($this->forms['image'])) {
            // 3. 파일 업로드 체크 Trait
            $this->upload_path = "/"; //"/upload";
            $this->upload_move = $this->uri; // "/images/widgets/".$this->uri; // 슬롯 안쪽으로 이동
            $this->fileUpload($this->forms, $this->upload_path);

            // json 수정 저장
            //$json = json_file_decode($this->path.DIRECTORY_SEPARATOR."widgets.json");
            $json = $this->actions['widgets'];
            $filename = basename($this->forms['image']);

            $_id = $this->_id;
            if($this->widget['files'][$_id] != $filename) {
                unlink($this->path.DIRECTORY_SEPARATOR.$this->widget['files'][$_id]);
                $this->widget['files'][$_id] = $filename;
            }

            $id = $this->widget_id;
            $this->actions['widgets'][$id] = $this->widget;
            json_file_encode($this->action_path, $this->actions);

            // 이미지 경로 다시 생성
            $this->images = [];
            if(isset($this->widget['files'])) {
                foreach($this->widget['files'] as $i => $item) {
                    $this->images[$i] = "/".$this->uri."/".$item;
                }
            }
        }

        $this->forms = [];
        return true;
    }

    public function delete()
    {
        $this->editable = false;

        // json 수정 저장
        $json = $this->actions['widgets'];
        $filename = basename($this->forms['image']);

        $_id = $this->_id;
        unlink($this->path.DIRECTORY_SEPARATOR.$this->widget['files'][$_id]);
        unset($this->widget['files'][$_id]);

        $id = $this->widget_id;
        $this->actions['widgets'][$id] = $this->widget;
        json_file_encode($this->action_path, $this->actions);


        // 이미지 경로 다시 생성
        $this->images = [];
        if(isset($this->widget['files'])) {
            foreach($this->widget['files'] as $i => $item) {
                $this->images[$i] = "/".$this->uri."/".$item;
            }
        }

        $this->forms = [];
        $this->_id = null;
        return true;
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

    // public function remove()
    // {
    //     //$this->mode = null;
    //     //$this->editable = false;

    //     $_id = $this->widget_id;
    //     //unset($this->widget[$_id]); //
    //     unset($this->actions['widgets'][$_id]);

    //     //dump($_id);
    //     //dump($this->widget);
    //     //dd($this->actions['widgets']);

    //     // 다시 저장
    //     json_file_encode($this->action_path, $this->actions);

    //     // 페이지 리로드 이벤트 발생
    //     // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
    //     $this->dispatch('page-realod');
    // }

    /**
     * 위젯 self 삭제합니다.
     */
    public function remove($key)
    {
        //dd($this->widget);

        $slot = www_slot();
        $filePath = resource_path('www/'.$slot);
        if(isset($this->widget['files'])) {
            foreach($this->widget['files'] as $file) {
                $filename = $filePath.$this->widget['route'].DIRECTORY_SEPARATOR.$file;
                if(file_exists($filename)) {
                    unlink($filename);
                }
            }
        }


        $temp = [];
        foreach($this->actions['widgets'] as $i => $item) {
            if($item['key'] == $key) {
                continue;
            }
            $temp []= $item;
        }

        $this->actions['widgets'] = $temp;
        json_file_encode($this->action_path, $this->actions); // 다시저장

        // 페이지 리로드 이벤트 발생
        // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
        $this->dispatch('page-realod');
    }


}
