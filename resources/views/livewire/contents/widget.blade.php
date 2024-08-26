<div>

    {{--@if ($popupSection)--}}
    <x-dialog-modal wire:model="popupSection" maxWidth="2xl">
        <x-slot name="content">
            @include('jiny-site-page::livewire.contents.markdown')
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-between">
                <div></div>
                <div class="text-right">
                    <x-button secondary wire:click="sectionClose">닫기</x-button>
                    <x-button primary wire:click="sectionUpdate">수정</x-button>
                </div>
            </div>
        </x-slot>
    </x-dialog-modal>
    {{--@endif--}}



</div>
