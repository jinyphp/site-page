<div class="mt-2 bg-gray-300">
    <x-flex class="gap-2">
        <button class="btn btn-primary btn-sm"
            wire:click="addWidget('widget-blade')">
            +Blade
        </button>

        <button class="btn btn-primary btn-sm"
            wire:click="addWidget('widget-markdown')">
            +Markdown
        </button>

        <button class="btn btn-primary btn-sm"
            wire:click="addWidget('widget-image')">
            +Image
        </button>

        <button class="btn btn-primary btn-sm"
            wire:click="addWidget('widget-table')">
            +Table
        </button>
    </x-flex>
</div>
