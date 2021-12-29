<?php

namespace Jiny\Pages\Http\Livewire;

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

use Webuni\FrontMatter\FrontMatter;
use Jiny\Pages\Http\Parsedown;

class LiveTrans extends Component
{
    public $content;
    protected $listeners = ['popupTransOpen'];

    public $text;
    public $transJson;
    
    public $popupTransEnable = false;
    
    private $trans;
    public $language='ko';
    public $transTextSrc;
    public $transTextDst;

    public function mount()
    {
        $path = resource_path('trans');
        $this->trans = polyglot($path);

        //dd($this->trans);
    }

    public function popupTransOpen($text)
    {
        $path = resource_path('trans');
        $this->trans = polyglot($path);

        $this->transTextSrc = $text; //원본 문자열
        $this->transTextDst = $this->trans->echo($text, $this->language); //첫번째 번역 결과물

        // 팝업 열기
        $this->popupTransEnable = true;
    }

    public function trans()
    {
        $path = resource_path('trans');
        $this->trans = polyglot($path);

        // 원본에 대한 OBJ 읽기
        if ($json = $this->trans->load($this->transTextSrc)) {

            // 배열 앞에 추가
            $obj = $this->trans->factory($this->transTextDst);   
            $lang = $this->language;       
            array_unshift($json[$lang], $obj);

            // 저장합니다.
            $this->trans->save($this->transTextSrc, $json);
        } else {
            // 새로운 번역기록을 생성
            $this->trans->makeTransText($this->transTextSrc, $this->language, $this->transTextDst);
        }

        $this->popupTransEnable = false;



        // 화면 갱신을 위하여 다시 분석.
        $markdown = (new Parsedown())->parse($this->content);
        $this->emitUp('reflashPage',$markdown);
    }

    /**
     * 번역 기록관리
     */
    public $popupHistory = false;
    public function history()
    {
        $path = resource_path('trans');
        $this->trans = polyglot($path);
        $this->transJson = $this->trans->load( $this->transTextSrc );
        //dd($this->transJson);

        // 이전팝업 닫고, 새로운 팝업 활성화
        $this->popupTransEnable = false;
        $this->popupHistory = true;
    }

    public function removeHistory($i)
    {
        unset($this->transJson['ko'][$i]);

        $path = resource_path('trans');
        $this->trans = polyglot($path);
        $this->trans->save($this->transTextSrc, $this->transJson);
        //dd($this->transJson);
    }

    public function closeHistory()
    {
        $this->popupTransEnable = true;
        $this->popupHistory = false;
    }

    public function popupClose()
    {
        $this->popupTransEnable = false;
        $this->popupHistory = false;
    }


    public function render()
    {
        return view("jinypage::livewire.trans");
    }
}