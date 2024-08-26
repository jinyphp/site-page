<?php
namespace Jiny\Site\Page\Http\Livewire;

use Jiny\Site\Page\Http\Livewire\SetPageRule;
class SetPostRule extends SetPageRule
{
    public function render()
    {
        return view("jiny-site-page::livewire.popup.postrules");
    }
}
