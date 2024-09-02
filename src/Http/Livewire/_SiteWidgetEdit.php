<?php
namespace Jiny\Site\Page\Http\Livewire;

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class SiteWidgetEdit extends Component
{
    public $mode;
    public $widgets;

    public function mount()
    {
        $this->widgets = sitePageWidgets();
    }

    public function render()
    {
        //$widgets = sitePageWidgets();
        //dd($widgets);

        if($this->mode == "edit") {
            return view("jiny-site-page::design.mode_drag");
        }

        return view("jiny-site-page::design.mode_edit",[
            //'widgets' => $widgets
        ]);

    }

    public function edit()
    {
        $this->mode = "edit";
    }

    public function delete($i)
    {
        unset($this->widgets[$i]);
        //dd($i);
    }

}
