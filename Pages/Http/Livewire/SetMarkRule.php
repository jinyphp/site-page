<?php

namespace Modules\Pages\Http\Livewire;

use Jiny\Pages\Http\Livewire\SetPageRule;
class SetMarkRule extends SetPageRule
{
    public function render()
    {
        return view("pages::livewire.popup.markrules");
    }
}
