<?php
namespace Jiny\Site\Page\Http\Livewire;

use Jiny\Site\Page\Http\Livewire\SetPageRule;
class SetMarkRule extends SetPageRule
{
    public function render()
    {
        return view("jiny-site-page::livewire.popup.markrules");
    }
}
