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

class WidgetImage extends Component
{
    public $actions = [];

    use WithFileUploads;
    use \Jiny\WireTable\Http\Trait\Upload;


    public $uri;
    public $slot;
    public $path;
    public $file;

    public $widget = [];
    public $widget_id;
    public $pages = [];
    public $content = "hello";

    public $images;

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
        }

        // $this->image = "/".$this->uri."/".$this->widget['path'];
        // 이미지 경로 다시 생성
        $this->images = [];
        if(isset($this->widget['path'])) {
            foreach($this->widget['path'] as $i => $item) {
                $this->images[$i] = "/".$this->uri."/".$item;
            }
        }



    }



    public function render()
    {
        return view("jiny-site-page::design.widgets.image");
    }


    public function create()
    {
        $this->editable = true;
    }

    public function store()
    {
        $this->editable = false;

        if(isset($this->forms['image'])) {
            // 3. 파일 업로드 체크 Trait
            $this->upload_path = "/"; //"/upload";
            $this->upload_move = $this->uri; // "/images/widgets/".$this->uri; // 슬롯 안쪽으로 이동
            $this->fileUpload($this->forms, $this->upload_path);

            // json 수정 저장
            $json = json_file_decode($this->path.DIRECTORY_SEPARATOR."widgets.json");
            $filename = basename($this->forms['image']);
            foreach($this->widget['path'] as $key => $item) {
                // 마지막 key 값을 얻기 위해서
            }
            $key++;
            $this->widget['path'][$key] = $filename;

            //dd($this->widget);

            $id = $this->widget_id;
            //dump($id);
            //dump($this->widget);
            $json[$id] = $this->widget;
            //dd($json);
            json_file_encode($this->path.DIRECTORY_SEPARATOR."widgets.json", $json);


            // 이미지 경로 다시 생성
            $this->images = [];
            if(isset($this->widget['path'])) {
                foreach($this->widget['path'] as $i => $item) {
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

        $this->forms['image'] = $this->widget['path'][$i];

        //dd($this->_id);
    }

    // public function modify()
    // {
    //     $this->mode = "modify";
    //     $this->editable = true;

    //     $this->forms['filename'] = $this->widget['path'];

    // }

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
            $json = json_file_decode($this->path.DIRECTORY_SEPARATOR."widgets.json");
            $filename = basename($this->forms['image']);

            $_id = $this->_id;
            if($this->widget['path'][$_id] != $filename) {
                unlink($this->path.DIRECTORY_SEPARATOR.$this->widget['path'][$_id]);
                $this->widget['path'][$_id] = $filename;
            }

            $id = $this->widget_id;
            $json[$id] = $this->widget;
            json_file_encode($this->path.DIRECTORY_SEPARATOR."widgets.json", $json);

            // 이미지 경로 다시 생성
            $this->images = [];
            if(isset($this->widget['path'])) {
                foreach($this->widget['path'] as $i => $item) {
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
        $json = json_file_decode($this->path.DIRECTORY_SEPARATOR."widgets.json");
        $filename = basename($this->forms['image']);

        $_id = $this->_id;
        unlink($this->path.DIRECTORY_SEPARATOR.$this->widget['path'][$_id]);
        unset($this->widget['path'][$_id]);

        $id = $this->widget_id;
        $json[$id] = $this->widget;
        json_file_encode($this->path.DIRECTORY_SEPARATOR."widgets.json", $json);


        // 이미지 경로 다시 생성
        $this->images = [];
        if(isset($this->widget['path'])) {
            foreach($this->widget['path'] as $i => $item) {
                $this->images[$i] = "/".$this->uri."/".$item;
            }
        }

        $this->forms = [];
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


}
