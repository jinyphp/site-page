<?php

namespace Modules\Pages\Http\Livewire;

use Modules\Pages\Http\Livewire\SetPageRule;
class SetPostRule extends SetPageRule
{
    public function render()
    {
        return view("pages::livewire.popup.postrules");
    }
}
