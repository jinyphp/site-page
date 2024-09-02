<div>
    {{-- 팝업 : 번역 입력창--}}

    <x-jet-dialog-modal wire:model="popupTransEnable">
        <x-slot name="title">
            {{ __('문자열 번역') }}
        </x-slot>
    
        <x-slot name="content">

            <div style="padding: .75rem">
                @if (isset($transTextSrc))                
                    {{$transTextSrc}}
                @endif
            </div>
            
            <div>
                {!! xSelect()
                    ->addOption("한국어",'ko')
                    ->addOption("영어",'en')
                    ->setAttribute('wire:model.defer',"language")
                !!}                
            </div>

            <div>
                {!! xTextarea("trans")->setAttribute('wire:model.defer',"transTextDst") !!}
            </div>
        </x-slot>
    
        <x-slot name="footer">
            <x-jet-danger-button class="ml-2" wire:click="history" wire:loading.attr="disabled">
                {{ __('기록') }}
            </x-jet-danger-button>


            <x-jet-secondary-button wire:click="popupClose" wire:loading.attr="disabled">
                {{ __('취소') }}
            </x-jet-secondary-button>
    
            <x-jet-danger-button class="ml-2" wire:click="trans" wire:loading.attr="disabled">
                {{ __('번역') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>



    {{-- 팝업 : 번역 기록 보기--}}

    <x-jet-dialog-modal wire:model="popupHistory">
        <x-slot name="title">
            {{ __('번역 기록') }}
        </x-slot>
    
        <x-slot name="content">
            @if ($transJson)
                @foreach ($transJson[$language] as $i => $item)
                    <div>
                        <x-flex-between>
                            <div>{{$item['timestamp']}} / {{$item['email']}}</div>
                            <div wire:click="removeHistory({{$i}})">del</div>
                        </x-flex-between>
                        <div>{{$item['text']}}</div>
                    </div>
                    <hr>                    
                @endforeach
            @endif
        </x-slot>
    
        <x-slot name="footer">

            <x-jet-secondary-button wire:click="closeHistory" wire:loading.attr="disabled">
                {{ __('취소') }}
            </x-jet-secondary-button>

        </x-slot>
    </x-jet-dialog-modal>
</div>