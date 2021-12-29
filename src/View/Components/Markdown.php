<?php
namespace Jiny\Pages\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;

class Markdown extends Component
{
    public function render()
    {
        return <<<'blade'
        @livewire('LiveMarkdown',['content'=>$slot->toHtml()])
    blade;
    }
}
