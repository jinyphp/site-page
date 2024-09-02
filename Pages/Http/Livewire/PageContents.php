<?php

namespace Modules\Pages\Http\Livewire;

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

use Webuni\FrontMatter\FrontMatter;
use Jiny\Pages\Http\Parsedown;

use \Jiny\Html\CTag;
use Livewire\WithFileUploads;

class PageContents extends Component
{
    use WithFileUploads;

    public $sectionId;
    public $content;
    public $upload;
    //public $type;

    public $forms = [];

    public function render()
    {
        if($this->sectionId) {

            $row = DB::table("jiny_pages_content")->where('id', $this->sectionId)->first();
            foreach($row as $key => $value) {
                $this->forms[$key] = $value;
            }

            if($row->type == "section" || $row->type == "vertical" || $row->type == "horizontal") {
                $form = 'pages::livewire.contents.section';
                return view("pages::livewire.contents.popup",['form'=>$form, 'row'=>$row]);
            } else

            // widget 컨덴츠 수정
            if($row->type == "markdown" || $row->type == "html" || $row->type == "blade") {
                if($row->path) {
                    $filename = resource_path('pages').$row->path;
                    if(file_exists($filename)) {
                        $this->content = file_get_contents($filename);
                    }
                } else {
                    $this->content = "";
                }

                $form = 'pages::livewire.contents.markdown';
                return view("pages::livewire.contents.popup",['form'=>$form, 'row'=>$row]);

            } else if($row->type == "image") {

                $form = 'pages::livewire.contents.image';
                return view("pages::livewire.contents.popup",['form'=>$form, 'row'=>$row]);
            }
        }


        // id값이 없는 경우
        return view("pages::livewire.contents.popup");
    }

    protected $listeners = ['sectionOpen','sectionClose'];
    public $popupSection = false;
    public function sectionOpen($id)
    {
        $this->popupSection = true;
        $this->sectionId = $id;
    }

    public function sectionClose()
    {
        $this->popupSection = false;
    }

    public function sectionUpdate()
    {
        if($this->sectionId) {
            $row = DB::table("jiny_pages_content")->where('id', $this->sectionId)->first();
            if($row->type == "markdown" || $row->type == "html" || $row->type == "blade") {
                if($row->path) {
                    $filename = resource_path('pages').$row->path;
                } else {
                    $filename = resource_path('pages').$row->route;
                    if(!is_dir($filename)) mkdir($filename,775, true);
                    $filename = $filename."/".$this->sectionId.".md";
                }

                file_put_contents($filename, $this->content);
            } else
            if($row->type == "image") {
                if($this->upload) {
                    $filename = $this->upload->store('photo',"public");
                    $src = storage_path('app').DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR.$filename;

                    $upload = public_path('images');
                    $dest = $upload.$row->route;
                    if(!is_dir($dest)) mkdir($dest,755, true);
                    $name = pathinfo($filename)['filename'].".".pathinfo($filename)['extension'];
                    //dump($src);
                    //dd($dest.DIRECTORY_SEPARATOR.$name);
                    copy($src, $dest.DIRECTORY_SEPARATOR.$name);
                    unlink($src);

                    $this->forms['path'] = $row->route."/".$name;
                }


            }

            // 정보저장
            DB::table("jiny_pages_content")
                ->where('id', $this->sectionId)
                ->update($this->forms);
        }

        $this->sectionId = null;
        $this->sectionClose();
    }


}
