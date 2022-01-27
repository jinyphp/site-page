<div>
    <button wire:click="sectionOpen">수정</button>

    @if ($popupSection)
    <x-dialog-modal wire:model="popupSection" maxWidth="2xl">
        <x-slot name="content">
            {{ $sectionId }} 팝업 섹션 관리
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
    @endif

</div>
