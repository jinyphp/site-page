<?php
namespace Jiny\Site\Page\Http\Livewire;

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;


class SitePageEdit extends Component
{
    public $uri;

    public $editMode = false;
    public $deletePopup = false;
    public $popupWindowWidth = '2xl';

    public $forms=[];
    public $message;
    public $deleteConfirm = false;

    use \Jiny\Widgets\Http\Trait\DesignMode;

    public function mount()
    {
        $this->uri = "/".Request::path();
    }

    public function render()
    {
        $viewFile = "jiny-site-page::pages.edit";
        return view($viewFile,[

        ]);
    }


    /**
     * 페이지 수정 모드 전환
     */
    public function pageEdit()
    {
        $this->editMode = true;
        $this->forms['uri'] = $this->uri;
        $this->message = null;
    }

    /**
     * 페이지 수정 저장
     */
    public function pageEditUpdate()
    {
        // 1. 중복 uri 체크
        $slot = www_slot();
        $path1 = resource_path("www/".$slot);
        $file = str_replace("/",DIRECTORY_SEPARATOR,$this->uri);
        $dst = str_replace("/",DIRECTORY_SEPARATOR,$this->forms['uri']);
        if(file_exists($path1.$dst)) {
            $this->message = "존재하는 uri 입니다.";
            return;
        }

        // 2. slot 리소스 변경
        $temp = substr($dst, 0, strrpos($dst, '/'));
        if(!file_exists($path1.$temp)) {
             mkdir($path1.$temp, 0777, true);
        }
        rename($path1.$file, $path1.$dst);

        // 3. actions 파일 변경
        $path2 = resource_path("actions");
        if(!file_exists($path2.$temp)) {
            mkdir($path2.$temp, 0777, true);
        }
        rename($path2.$file.".json", $path2.$dst.".json");

        $this->editMode = false;
        $this->forms = [];
        $this->message = null;

        // 페이지 리로드 이벤트 발생
        // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
        $this->dispatch('page-realod');
    }

    /**
     * 페이지 수정 취소
     */
    public function pageEditCancel()
    {
        $this->editMode = false;
        $this->deletePopup = false;
        $this->deleteConfirm = false;
    }

    /**
     * 페이지 삭제 모드 전환
     */
    public function pageEditDelete()
    {
        $this->deletePopup = true;
    }

    /**
     * 페이지 삭제 확인
     */
    public function pageEditDeleteConfirm()
    {
        // 1.action 설정값을 삭제합니다.
        $path2 = resource_path("actions");
        $file = str_replace("/",DIRECTORY_SEPARATOR,$this->uri);
        if(file_exists($path2.$file.".json")) {
            unlink($path2.$file.".json");

            if(is_dir($path2.$file)) {
                rmdir($path2.$file);
            }
        }


        // 2. slot 리소스 파일을 삭제합니다.
        $path1 = resource_path("www");
        $path1 .= DIRECTORY_SEPARATOR.www_slot();
        $file = str_replace("/",DIRECTORY_SEPARATOR,$this->uri);
        if(file_exists($path1.$file)) {
            $dir = scandir($path1.$file);
            //dd($dir);
            // 부속파일 삭제
            foreach($dir as $item) {
                if($item == "." || $item == "..") continue;
                unlink($path1.$file.DIRECTORY_SEPARATOR.$item);
            }

            if(is_dir($path1.$file)) {
                rmdir($path1.$file);
            }
         }

        $this->editMode = false;

        $this->deletePopup = false;
        $this->deleteConfirm = false;

        // 페이지 리로드 이벤트 발생
        // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
        $this->dispatch('page-realod');
    }


}
