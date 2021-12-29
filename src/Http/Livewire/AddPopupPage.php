<?php

namespace Jiny\Pages\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;

class AddPopupPage extends Component
{
    public $actions;
    public $uri;


    public function render()
    {
        return view("jinypage::livewire.popup.add");
    }

    /**
     * 팝업창 관리
     */
    protected $listeners = ['popupRuleOpen','popupRuleClose'];
    public $popupRule = false;
    public function popupRuleOpen()
    {
        $this->popupRule = true;
    }

    public function popupRuleClose()
    {
        $this->popupRule = false;
    }


    public function create()
    {
        //$uri = Route::current()->uri;
        //dd($this->uri);
        $this->form['enable'] = 1;
        $this->form['route'] = $this->uri;
        $this->popupRuleOpen();

    }


    public $form = [];
    public function save()
    {
        //유효성 검사
        if (isset($this->actions['validate'])) {
            $validator = Validator::make($this->form, $this->actions['validate'])->validate();
        }

        // 시간정보 생성
        $this->form['created_at'] = date("Y-m-d H:i:s");
        $this->form['updated_at'] = date("Y-m-d H:i:s");


        // 데이터 삽입
        DB::table("site_route")->insertGetId($this->form);

        $this->popupRuleClose();

        // Livewire Table을 갱신을 호출합니다.
        //$this->emit('refeshTable');

        return redirect()->to($this->form['route']);
    }

}
