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
 * 페이지에 새로운 위젯을 추가합니다.
 */
class SiteWidgetAdd extends Component
{
    public $pos;
    use \Jiny\Widgets\Http\Trait\DesignMode;

    public function mount()
    {

    }

    public function render()
    {
        $viewFile = "jiny-site-page::pages.widget_add";
        return view($viewFile,[
        ]);
    }

    public function create($pos=null)
    {
        $this->dispatch('siteWidgetCreate', $pos);
        // $this->popupForm = true;
        // $this->pos = $pos;

        // // 위젯목록을 읽어 옵니다.
        // $this->widgetList = widgetList();
        // //dd($this->widgetList);
    }


}
