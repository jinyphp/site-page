<?php

namespace Jiny\Pages\Http\Livewire;

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

use Webuni\FrontMatter\FrontMatter;
use Jiny\Pages\Http\Parsedown;

use \Jiny\Html\CTag;

class PageContents extends Component
{

    public $sectionId;

    public function render()
    {
        return view("jinypage::livewire.contents.section");
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

    }


}
