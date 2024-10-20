<?php
namespace Jiny\Site\Page\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Request;
use Livewire\Attributes\On;

class SitePageHtml extends Component
{
    public $uri;
    public $filepath;

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
        $this->filepath = $this->getFilePath($this->uri);

        $this->slot = www_slot();
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

        if(file_exists($filePath.".html")) {
            return $filePath.".html";
        } else if(is_dir($filePath)) {
            $filePath = $filePath.DIRECTORY_SEPARATOR."index";
            if(file_exists($filePath.".html")) {
                return $filePath.".html";
            }
        }

        return false;
    }



    public function render()
    {
        if($this->filepath) {
            //dd($this->filepath);
            $body = file_get_contents($this->filepath);
            $this->content = $body;
            // $mk = \Jiny\Markdown\MarkdownPage::instance();
            // $mk->parser($body); // 프론트메터 파싱

            // 마크다운 변환
            //$html = (new \Parsedown())->parse($mk->content);

            // 북마크
            //$bookmark = $mk->parseBookmark($html);
            $bookmark = [];
            //$html = $mk->html; // 북마크 포함 html 반환

            //dd($body);
            if($this->mode == "edit") {
                $viewFile = 'jiny-site-page::site.html.edit';
            } else {
                $viewFile = 'jiny-site-page::site.html.content';
            }

            return view($viewFile,[
                'html'=>$body,
                'bookmark'=>$bookmark,
                    //'data'=>$mk->data
                ]);
        } else {
            return view('jiny-site-page::site.html.404');
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
