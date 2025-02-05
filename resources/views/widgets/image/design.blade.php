<div class="card mb-3">
    @if($editable)
    {{-- 수정모드 --}}
    <div class="card-body">
        @includeIf($widget['view']['form'])
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
                @if(isset($forms[$i]))
                {{$forms[$i]}}
                @endif
                <x-click wire:click="edit({{$i}})">
                    <img src="{{$img}}" alt="">
                </x-click>
            </div>
            @endforeach

            <div>
                <button class="btn btn-primary btn-sm" wire:click="create">이미지 추가</button>
            </div>
            {{-- <div class="d-flex align-items-center justify-content-center" style="min-width:100px; min-height:100px;">
                <p class="text-muted">이미지 추가</p>
                <span class="text-info" wire:click="create">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                </span>
            </div> --}}
        </div>
    </div>
    @endif
</div>
