<?php
namespace Jiny\Site\Page\Http\Livewire;

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

use Webuni\FrontMatter\FrontMatter;
use Jiny\Site\Page\Http\Parsedown;

use Livewire\Attributes\On;

class WidgetMarkdown extends Component
{
    public $uri;
    public $slot;
    public $path;
    public $file;

    public $widget = [];
    public $widget_id;
    public $pages = [];
    public $content = "hello";

    public $mode;
    public $editable = false;
    public $markdown;
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

            //$this->path = $path.DIRECTORY_SEPARATOR.$this->widget['path'];
            $this->file = $path.DIRECTORY_SEPARATOR.$this->widget['path'];
            $this->path = $path; //.DIRECTORY_SEPARATOR.$this->widget['path'];
        }

        // 파일읽기 및 마크다운 변환
        if(is_file($this->file)) {
            $this->markdown = file_get_contents($this->file);

            $Parsedown = new Parsedown();
            $this->content = $Parsedown->parse($this->markdown);
        }


    }

    public function render()
    {
        // if($this->mode == "modify") {

        //     return view("jiny-site-page::design.widgets.markdown_edit");
        // }

        /*
        if(isset($this->widget['path'])) {
            $path = resource_path('www');
            if($this->slot) {
                $path = $path.DIRECTORY_SEPARATOR.$this->slot;
                $path .= DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $this->uri);
            }

            $path = $path.DIRECTORY_SEPARATOR.$this->widget['path'];
            if(is_file($path)) {
                $body = file_get_contents($path);

                $Parsedown = new Parsedown();
                $this->content = $Parsedown->parse($body);
            }
        }
            */

        return view("jiny-site-page::design.widgets.markdown");
    }


    public function modify()
    {
        $this->mode = "modify";
        $this->editable = true;

        // if(is_file($this->path)) {
        //     $this->markdown = file_get_contents($this->path);
        // }
        if(is_file($this->file)) {
            $this->forms['markdown'] = file_get_contents($this->file);
        }

        $this->forms['filename'] = $this->widget['path'];
    }

    public function cencel()
    {
        $this->mode = null;
        $this->editable = false;

    }

    public function update()
    {
        $this->mode = null;
        $this->editable = false;

        //file_put_contents($this->path, $this->markdown);
        //dd($this->path);
        if(isset($this->forms['markdown'])) {
            file_put_contents($this->file, $this->forms['markdown']);
        }

        $Parsedown = new Parsedown();
        $this->content = $Parsedown->parse($this->markdown);


        // json 수정 저장
        $json = json_file_decode($this->path.DIRECTORY_SEPARATOR."widgets.json");
        foreach($json as $i => $item) {
            if($item['path'] == $this->widget['path']) {
                break;
            }
        }

        // 파일이름 변경 확인
        if($this->widget['path'] != $this->forms['filename']) {
            // 파일명 변경
            $oldFile = $this->path.DIRECTORY_SEPARATOR.$this->widget['path'];
            $newFile = $this->path.DIRECTORY_SEPARATOR.$this->forms['filename'];
            rename($oldFile, $newFile);

            // json 수정 저장
            $this->widget['path'] = $this->forms['filename'];
            $json[$i] = $this->widget;
            json_file_encode($this->path.DIRECTORY_SEPARATOR."widgets.json", $json);

        }



        $this->forms = [];
    }

    public function delete()
    {
        $this->mode = null;
        $this->editable = false;

        if(isset($this->widget['path'])) {
            $path = resource_path('www');

            if($this->slot) {
                $path = $path.DIRECTORY_SEPARATOR.$this->slot;
                $path .= DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $this->uri);
            }

            //$json = $path.DIRECTORY_SEPARATOR."widgets.json";
            $json = json_file_decode($path.DIRECTORY_SEPARATOR."widgets.json");
            foreach($json as $i => $item) {
                if($item['path'] == $this->widget['path']) {
                    break;
                }
            }
            unset($json[$i]); //

            // 다시 저장
            json_file_encode($path.DIRECTORY_SEPARATOR."widgets.json", $json);


            // 페이지 리로드 이벤트 발생
            // 현재 목록을 삭제하였기 때문에, 페이지 전체 리로드가 필요함
            $this->dispatch('page-realod');
        }

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
