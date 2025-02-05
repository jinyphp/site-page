<?php
namespace Jiny\Site\Page\Http\Livewire;

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class SiteWidgetMove extends Component
{
    use \Jiny\Widgets\Http\Trait\DesignMode;
    public function render()
    {
        return view('jiny-site-page::pages.move');
    }

}
