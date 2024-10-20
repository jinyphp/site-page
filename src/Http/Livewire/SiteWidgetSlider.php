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

class SiteWidgetSlider extends Component
{
    public $actions = [];
    public $action_path;

    use WithFileUploads;
    use \Jiny\WireTable\Http\Trait\Upload;

    public $code; //슬라이더 코드

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

    public $viewFile;

    use \Jiny\Widgets\Http\Trait\DesignMode;

    public $sliders = [];

    public function mount()
    {
        if(!$this->viewFile) {
            $this->viewFile = "jiny-site-page::widgets.slider.hero2";
        }

        if($this->code) {
            $this->sliders = site_slider($this->code); // helper 함수로 처리
        }

    }

    public function render()
    {
        if(!$this->viewFile) {
            return view("jiny-site-page::error",[
                'message' => '위젯 viewFile이 지정되어 있지 않습니다.'
            ]);
        }

        return view($this->viewFile);
    }


}
