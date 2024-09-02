<div class="mt-2">
    <x-flex class="gap-2">
        <button class="btn btn-primary btn-sm"
            wire:click="addWidget('blade')">
            +Blade
        </button>

        <button class="btn btn-primary btn-sm"
            wire:click="addWidget('markdown')">
            +Markdown
        </button>

        <button class="btn btn-primary btn-sm"
            wire:click="addWidget('image')">
            +Image
        </button>

        <button class="btn btn-primary btn-sm"
            wire:click="addWidget('table')">
            +Table
        </button>
    </x-flex>
</div>
