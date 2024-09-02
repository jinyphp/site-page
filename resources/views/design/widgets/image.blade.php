<article>
    @if(!$design)
    {{-- 일반모드 --}}
    <div class="d-flex gap-2">
        @foreach( $images as $img)
        <div>
            <img src="{{$img}}" alt="">
        </div>
        @endforeach
    </div>
    @else
    {{-- 디자인 모드 --}}
    <div class="card mb-3">
        <div class="card-header">
            <x-flex-between>
                <div>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                            <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                            <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z"/>
                        </svg>
                    </span>
                    <span>
                        {{-- {{$widget['path']}} --}}
                        images
                    </span>
                </div>
                <div>
                    @if(!$editable)
                    <span class="text-info" wire:click="create">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                        </svg>
                    </span>
                    @endif
                </div>
            </x-flex-between>
        </div>


        @if($editable)
        {{-- 수정모드 --}}
        <div class="card-body">
            {{-- <div class="mb-3">
                <label class="form-label">파일이름</label>
                {!! xInputText()
                    ->setWire('model.defer',"forms.filename")
                    ->setWidth("standard")
                !!}
            </div> --}}
            <div class="mb-3">
                <label for="simpleinput" class="form-label">사진</label>
                <input type="file" class="form-control"
                            wire:model.defer="forms.image">
                <p>
                    @if(isset($forms['image']))
                    {{$forms['image']}}
                    @endif
                </p>
            </div>

        </div>
        <div class="card-footer">
            @if(is_numeric($_id))
            <x-flex-between class="gap-4 mt-2">
                <div>
                    <button class="btn btn-danger btn-sm" wire:click="delete">삭제</button>
                </div>
                <div>
                    <button class="btn btn-secondary btn-sm" wire:click="cencel">취소</button>
                    <button class="btn btn-info btn-sm" wire:click="update">수정</button>
                </div>
            </x-flex-between>
            @else
            <x-flex-between class="gap-4 mt-2">
                <div>

                </div>
                <div>
                    <button class="btn btn-secondary btn-sm" wire:click="cencel">취소</button>
                    <button class="btn btn-info btn-sm" wire:click="store">추가</button>
                </div>
            </x-flex-between>
            @endif
        </div>
        @else
        {{-- 편집 모드 --}}
        <div class="card-body">
            <div class="d-flex gap-2">
                @foreach( $images as $i => $img)
                <div>
                    <x-click wire:click="edit({{$i}})">
                        <img src="{{$img}}" alt="">
                    </x-click>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif



</article>
