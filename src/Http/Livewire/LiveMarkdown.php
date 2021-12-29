<?php

namespace Jiny\Pages\Http\Livewire;

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

use Webuni\FrontMatter\FrontMatter;
use Jiny\Pages\Http\Parsedown;

use \Jiny\Html\CTag;

class LiveMarkdown extends Component
{
    protected $listeners = ['reflashPage'];

    public $content;  // 원본 데이터
    public $markdown; // 변환데이터

    public $admin = true;
    public $trans = true;
    public $transMode = false;
    public $transTotal = 0;
    public $transCount = 0;

    public function mount()
    {
    }

    public function render()
    {
        $Parsedown = new Parsedown();
        $Parsedown->admin = $this->admin;
        $Parsedown->trans = $this->trans;
        $Parsedown->transMode = $this->transMode;
        $this->markdown = $Parsedown->parse($this->content);

        // 마크다운 번역후
        // 싱글턴으로 기록된 결과 읽어오기
        $this->transTotal = polyglot()->total;
        $this->transCount = polyglot()->count;
        //$objHTml = $this->transParser($this->content);

        return view("jinypage::livewire.markdown");
    }

    public function reflashPage($markdown)
    {
        $this->markdown = $markdown;
    }

}