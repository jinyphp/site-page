<?php
namespace Modules\Markdown\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;

class Markdown extends Component
{
    public function render()
    {
        // 이중호출처리
        // 라이브와어어 호출로 감싸서 처리
        return <<<'blade'
        @livewire('LiveMarkdown',['content'=>$slot->toHtml()])
    blade;

    }
}
