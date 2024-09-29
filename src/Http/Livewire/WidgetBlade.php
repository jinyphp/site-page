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
 * Drag Blade Widget
 */
class WidgetBlade extends Component
{
    public $actions = [];
    public $action_path;

    public $uri;
    public $slot;
    public $path;
    public $file;

    public $widget = [];
    public $widget_id;
    public $pages = [];
    public $content = "blade file";

    public $bladeFile;

    public $mode;
    public $editable = false;
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

            $this->file = $path.DIRECTORY_SEPARATOR.$this->widget['path'];
            $this->path = $path; //.DIRECTORY_SEPARATOR.$this->widget['path'];
        }

        // Blade
        $this->makeBaldeFilePath();

        // actions 파일 경로 체크
        $this->action_path = resource_path('actions');
        $this->action_path .= DIRECTORY_SEPARATOR;
        $this->action_path .= str_replace('/', DIRECTORY_SEPARATOR, $this->uri);
        $this->action_path .= ".json";

        // actions 데이터 읽기
        $this->actions = $this->loadActions();
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

    private function makeBaldeFilePath()
    {
        if(is_file($this->file)) {
            $this->bladeFile = "www::".$this->slot.".".str_replace("/",".",$this->uri);
            $this->bladeFile .= ".".str_replace('.blade.php',"",$this->widget['path']);
        } else {
            $this->bladeFile = "";
        }
    }

    public function render()
    {
        return view("jiny-site-page::widgets.blade");
    }


    public function modify()
    {
        $this->mode = "modify";
        $this->editable = true;

        if(is_file($this->file)) {
            $this->forms['blade'] = file_get_contents($this->file);
        }



        $this->forms['filename'] = $this->widget['path'];


    }

    public function cencel()
    {
        $this->mode = null;
        $this->editable = false;

        $this->forms = [];
    }

    public function update()
    {
        $this->mode = null;
        $this->editable = false;

        if(isset($this->forms['blade'])) {
            file_put_contents($this->file, $this->forms['blade']);
        }


        // json 수정 저장
        // $json = json_file_decode($this->path.DIRECTORY_SEPARATOR."widgets.json");
        // foreach($json as $i => $item) {
        //     if($item['path'] == $this->widget['path']) {
        //         break;
        //     }
        // }

        // 파일이름 변경 확인
        // if($this->widget['path'] != $this->forms['filename']) {
        //     // 파일명 변경
        //     $oldFile = $this->path.DIRECTORY_SEPARATOR.$this->widget['path'];
        //     $newFile = $this->path.DIRECTORY_SEPARATOR.$this->forms['filename'];
        //     rename($oldFile, $newFile);

        //     // json 수정 저장
        //     $this->widget['path'] = $this->forms['filename'];
        //     $json[$i] = $this->widget;
        //     json_file_encode($this->path.DIRECTORY_SEPARATOR."widgets.json", $json);

        //     // blade 파일 path이름 다시 생성
        //     //$this->makeBaldeFilePath();
        //     $this->bladeFile = "www::".$this->slot.".".str_replace("/",".",$this->uri);
        //     $this->bladeFile .= ".".str_replace('.blade.php',"",$this->widget['path']);
        //     //dd($this->bladeFile);
        // }

        // 파일이름 변경 확인
        if($this->isEditFilename()) {
            $this->widgetJsonFileUpdate($this->widget_id);

            $this->bladeFile = "www::".$this->slot.".".str_replace("/",".",$this->uri);
            $this->bladeFile .= ".".str_replace('.blade.php',"",$this->widget['path']);
        }



        $this->forms = [];
    }

    private function isEditFilename()
    {
        // 파일이름 변경 확인
        if(isset($this->forms['filename']) &&
            $this->widget['path'] != $this->forms['filename']) {
            // 파일명 변경
            $oldFile = $this->path.DIRECTORY_SEPARATOR.$this->widget['path'];
            if(file_exists($oldFile)) {
                $newFile = $this->path.DIRECTORY_SEPARATOR.$this->forms['filename'];
                rename($oldFile, $newFile);

                // json 수정 저장
                $this->widget['path'] = $this->forms['filename'];
            }

            return true;
        }

        return false;
    }

    private function widgetJsonFileUpdate($id)
    {
        // json 수정 저장
        $this->actions['widgets'][$id] = $this->widget;
        json_file_encode($this->action_path, $this->actions);
    }

    public function delete()
    {
        $this->mode = null;
        $this->editable = false;

        if(isset($this->widget['path'])) {

            $json = $this->actions['widgets'];
            foreach($json as $i => $item) {
                if($item['path'] == $this->widget['path']) {
                    break;
                }
            }
            unset($this->actions['widgets'][$i]); //

            // 다시 저장
            json_file_encode($this->action_path, $this->actions);

            // 페이지 리로드 이벤트 발생
            // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
            $this->dispatch('page-realod');
        }

        $this->forms = [];
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
