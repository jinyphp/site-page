<x-wire-dialog-modal wire:model="popupForm" :maxWidth="$popupWindowWidth">
    <x-slot name="title">
        {{ __('위젯을 추가합니다.') }} {{$pos}}
    </x-slot>

    <x-slot name="content">

        <style>
            /* Main container to hold the grid */
            .grid-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 equal-width columns */
            gap: 10px; /* Space between grid items */

            overflow-y: auto; /* Enable vertical scrolling */
            overflow-x: hidden; /* Disable horizontal scrolling */
            padding: 10px;
            }

            /* Individual grid items */
            .grid-item {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            border: 1px solid #ddd;
            height: 150px; /* Fixed height for each item */
            box-sizing: border-box;
            }
        </style>


        <x-ui-divider>
            Raw Widgets
        </x-ui-divider>
        <div class="grid-container">
            @foreach($widgetList as $i => $item)
            <div class="grid-item" wire:click="store('{{$item['code']}}')">
                @if(isset($item['image']) && $item['image'])
                <img src="{{$item['image']}}" alt="">
                @else
                    <h5>{{$item['title']}}</h5>

                    @if(isset($item['description']))
                    <p>
                        {{$item['description']}}
                    </p>
                    @endif
                @endif
            </div>
            @endforeach
        </div>

        <x-ui-divider>
            Template Widgets
        </x-ui-divider>

        <div class="grid-container">
            @foreach(widgetTemplates() as $i => $item)
            <div class="grid-item" wire:click="store('{{$item['code']}}')">
                @if(isset($item['image']) && $item['image'])
                <img src="{{$item['image']}}" alt="">
                @else
                    <h5>{{$item['title']}}</h5>

                    @if(isset($item['description']))
                    <p>
                        {{$item['description']}}
                    </p>
                    @endif
                @endif
            </div>
            @endforeach
        </div>


    </x-slot>


    <x-slot name="footer">
        <div class="flex justify-between">
            <div>
                <p>기존에 있는 파일을 드레그 하여 업로드 및 페이지를 구성할 수 있습니다.</p>
            </div>
            <div class="text-right">
                <button type="button" class="btn btn-secondary"
                    wire:click="cancel">취소</button>
            </div>
        </div>
    </x-slot>
</x-wire-dialog-modal>
