<div>
    <x-loading-indicator/>

    <style>
        .file-tree li {
            padding: 10px 0px 0px 10px;
            border-left-color: gray;
            border-left-width: 1px;

            border-top-color: #cccccc;
            border-top-width: 1px;
            border-top-style: dashed;
        }
    </style>

    <x-card>
        <x-card-header>
            Resource View Files
        </x-card-header>
        <x-card-body>
            {!! xDirectory($rows)
                ->addClass("file-tree")
                ->addFirstItem(
                    xLink( xIcon($name="plus-square-dotted", $type="bootstrap")->setClass("w-4 h-4") )
                    ->setAttribute("wire:click", "$"."emit('create','')")
                )
            !!}
        </x-card-body>
    </x-card>

</div>
