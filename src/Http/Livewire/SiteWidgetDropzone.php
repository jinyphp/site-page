<?php
namespace Jiny\Site\Page\Http\Livewire;

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Livewire\Attributes\On;

/**
 * 드레그로 위젯 등록
 */
class SiteWidgetDropzone extends Component
{
    use \Jiny\Widgets\Http\Trait\DesignMode;

    public function render()
    {
        return view("jiny-site-page::design.drop");
    }

}
