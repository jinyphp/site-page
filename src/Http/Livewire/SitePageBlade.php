<?php
namespace Jiny\Site\Page\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Request;
use Livewire\Attributes\On;

class SitePageBlade extends Component
{
    public $uri;
    public $filepath;
    public $bladePath;

    public $mode;
    public $content;

    public $slot;
    public $design;

    #[On('design-mode')]
    public function designMode($mode=null)
    {
        if($this->design) {
            $this->design = false;
            $this->mode = false;
        } else {
            $this->design = true;
            $this->mode = "edit";
        }
    }

    public function mount()
    {
        $this->uri = Request::path();
        $this->slot = www_slot();
        $this->filepath = $this->getFilePath($this->uri);


    }

    private function getFilePath($uri)
    {
        $prefix_www = "www";
        $filename = str_replace('/', DIRECTORY_SEPARATOR, $uri);
        $filename = ltrim($filename, DIRECTORY_SEPARATOR);

        $slot = www_slot();
        $slotKey = $prefix_www.DIRECTORY_SEPARATOR.$slot; // slot path
        $path = resource_path($slotKey);

        $filePath = $path.DIRECTORY_SEPARATOR.$filename;



        if(file_exists($filePath.".blade.php")) {
            $this->bladePath = "www::".$this->slot.".".str_replace('/','.',$uri);
            return $filePath.".blade.php";
        } else if(is_dir($filePath)) {
            $filePath = $filePath.DIRECTORY_SEPARATOR."index";
            if(file_exists($filePath.".blade.php")) {
                $this->bladePath = "www::".$this->slot.".".str_replace('/','.',$uri).".index";
                return $filePath.".blade.php";
            }
        }

        return false;
    }



    public function render()
    {
        if($this->filepath) {

            $body = file_get_contents($this->filepath);
            $this->content = $body;
            $bookmark = [];

            if($this->mode == "edit") {
                $viewFile = 'jiny-site-page::site.blade.edit';
                return view($viewFile,[
                    'html'=>$body,
                    'bookmark'=>$bookmark
                    ]);
            } else {
                $blade = "www::".$this->slot.".".str_replace('/','.',$this->uri).".index";
                $viewFile = 'jiny-site-page::site.blade.content';
                return view($viewFile,[
                    'blade'=>$blade,
                    //'html'=>$body,
                    'bookmark'=>$bookmark
                    ]);
            }


        } else {
            return view('jiny-site-page::site.blade.404');
        }
    }

    /**
     * 적용
     */
    public function apply()
    {
        //$this->mode = null;
        file_put_contents($this->filepath, $this->content);
    }

    /**
     * 저장
     */
    public function save()
    {
        $this->mode = null;
        $this->design = false;
        file_put_contents($this->filepath, $this->content);
    }

    /**
     * 삭제 확인 팝업
     */
    public $deleteConfirmPopup = false;
    public function delete()
    {
        if(file_exists($this->filepath)) {
            unlink($this->filepath);
            $this->filepath = null;

            $this->dispatch('page-realod');
        }
    }

    /**
     * 삭제 확인 팝업
     */
    public function confirmDelete()
    {
        $this->deleteConfirmPopup = true;
    }


}
