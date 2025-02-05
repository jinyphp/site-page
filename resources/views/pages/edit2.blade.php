{{-- 페이지 수정 버튼 --}}
<div class="d-flex justify-content-end gap-2 mb-4">
    <div>
        <button class="btn btn-info" wire:click="pageEdit">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
            </svg>
            <span>
                페이지수정
            </span>
        </button>
    </div>
</div>

{{-- 페이지 수정 팝업 --}}
@if($editMode)
<x-wire-dialog-modal wire:model="editMode" :maxWidth="$popupWindowWidth">
    <x-slot name="title">
        페이지 수정
    </x-slot>

    <x-slot name="content">
        @if($deletePopup)
        <div>페이지를 삭제합니다.</div>
        @else

        <x-form-hor>
            <x-form-label>접속경로</x-form-label>
            <x-form-item>
                {!! xInputText()
                    ->setAttribute('name',"uri")
                    ->setWire('model.defer',"forms.uri")
                    ->setWidth("standard")
                !!}
            </x-form-item>
        </x-form-hor>

        @endif

        <div>{{$message}}</div>
    </x-slot>


    <x-slot name="footer">
        <div class="flex justify-between">
            <div>
                @if($deletePopup)
                <button type="button" class="btn btn-danger"
                    wire:click="pageEditDeleteConfirm">예,삭제합니다.</button>
                @else
                <button type="button" class="btn btn-danger"
                    wire:click="pageEditDelete">삭제</button>
                @endif
            </div>
            <div class="text-right">
                <button type="button" class="btn btn-secondary"
                    wire:click="pageEditCancel">취소</button>
                <button class="btn btn-info mb-2" wire:click="pageEditUpdate">변경</button>
            </div>
        </div>
    </x-slot>
</x-wire-dialog-modal>
@endif
