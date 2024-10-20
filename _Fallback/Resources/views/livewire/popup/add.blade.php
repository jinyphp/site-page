<div class="h-100">
    <x-card class="h-100">
        <x-card-header>
            <h5 class="card-title">페이지추가</h5>
            <h6 class="card-subtitle text-muted">정적/마크다운/포스트를 페이지로 등록합니다.</h6>
        </x-card-header>
        <x-card-body>
            <a href="javascript: void(0);" wire:click="create">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </a>
        </x-card-body>
    </x-card>


    <!-- 팝업 Rule 수정창 -->
    @if ($popupRule)
    <x-dialog-modal wire:model="popupRule" maxWidth="2xl">
        <x-slot name="content">

            @include("fallback::route.form")

        </x-slot>
        <x-slot name="footer">
            <div class="flex justify-between">
                <div></div>
                <div class="text-right">
                    <x-button secondary wire:click="popupRuleClose">취소</x-button>
                    <x-button primary wire:click="save">추가</x-button>
                </div>
            </div>
        </x-slot>
    </x-dialog-modal>
    @endif

</div>
