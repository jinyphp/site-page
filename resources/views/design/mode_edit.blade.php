<div>
    @foreach ($widgets as $i => $widget)
    <div style="position: relative;">
        <div style="position: absolute;top:4px;right:4px;z-index:50;">
            <x-click wire:click="delete('{{$i}}')">Delete</x-click>
        </div>
        <div>
            {{$widget['element']}}
        </div>
    </div>
    @endforeach
</div>
