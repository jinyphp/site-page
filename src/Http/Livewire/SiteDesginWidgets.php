<?php
namespace Jiny\Site\Page\Http\Livewire;

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;


class SiteDesginWidgets extends Component
{
    public $uri;
    public $slot;

    public function mount()
    {
        $this->uri = "/".Request::path();
        $this->slot = $this->getSlot();
    }

    public function render()
    {
        return view("jiny-site-page::design.widgets");
    }

    public function addWidget($widget)
    {
        DB::table('site_page_widgets')->insert([
            'route' => $this->uri,
            'element' => $widget
        ]);

        //dump($this->uri);
        //dd($widget);

        $this->dispatch('page-realod');

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
