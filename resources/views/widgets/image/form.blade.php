{{-- <div class="mb-3">
    <label class="form-label">파일이름</label>
    {!! xInputText()
        ->setWire('model.defer',"forms.filename")
        ->setWidth("standard")
    !!}
</div> --}}

{{-- <div class="mb-3">
    <label for="simpleinput" class="form-label">사진</label>
    <input type="file" class="form-control"
                wire:model.defer="forms.image">
    <p>
        @if(isset($forms['image']))
        {{$forms['image']}}
        @endif
    </p>
</div> --}}

<div class="mb-3">
    @if(isset($forms['image']))
        {{$forms['image']}}
    @endif
</div>

<div>
    <label for="imageUpload">이미지 추가</label>
    <div class="input-group">
        <input type="file" class="form-control" id="imageUpload"
            wire:model.live="forms.image"
            accept="image/*"
            wire:loading.attr="disabled">
    </div>

    <div wire:loading wire:target="forms.image" class="inline-flex items-center ml-2">
        <div class="spinner-border spinner-border-sm text-primary" role="status">
            <span class="visually-hidden">업로드 중...</span>
        </div>
        <span class="text-primary ms-2">파일 업로드 중...</span>
    </div>
</div>
