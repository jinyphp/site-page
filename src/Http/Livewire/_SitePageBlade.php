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

class SitePageBlade extends Component
{
    public $uri;
    public $slot;
    public $path;

    public $widget = [];
    public $pages = [];


    public function mount()
    {
        $this->uri = Request::path();
        $this->slot = $this->getSlot();
    }

    public function render()
    {
        //dd($this->pages);
        if(isset($this->widget['path'])) {
            $path = resource_path('www');
            if($this->slot) {
                $path = $path.DIRECTORY_SEPARATOR.$this->slot;
                $path .= DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $this->uri);
            }

            $file = $path.DIRECTORY_SEPARATOR.$this->widget['path'];
            if(is_file($file)) {

                $blade = "www::".$this->slot.".".str_replace("/",".",$this->uri);
                $blade .= ".".str_replace('.blade.php',"",$this->widget['path']);
                //dd($blade);
            }
        } else {
            $blade = "";
        }

        return view("jiny-site-page::design.widgets.blade",[
            'bladeFile' => $blade
        ]);
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
