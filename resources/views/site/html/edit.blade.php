<div>
    <h1>Html 수정</h1>
    <p>Html 코드로 작성하세요.</p>
    <textarea wire:model="content"
        style="width: 100%; min-height: 30em; outline: none; border: 1px solid #ccc;"
        onfocus="this.style.boxShadow='0 0 0 2px #e9ecef';"
        onblur="this.style.boxShadow='none';"></textarea>

    <p>최근 수정일 : {{date("Y-m-d H:i:s", filemtime($this->filepath))}}</p>

    <div class="d-flex justify-content-between gap-2 my-4">
        <div>
            @if($deleteConfirmPopup)
            <button class="btn btn-outline-danger" wire:click="delete">정말 삭제할까요?</button>
            @else
            <button class="btn btn-danger" wire:click="confirmDelete">삭제</button>
            @endif

        </div>
        <div>
            <button class="btn btn-info" wire:click="apply">적용</button>
            <button class="btn btn-primary" wire:click="save">수정</button>
        </div>

    </div>
</div>
