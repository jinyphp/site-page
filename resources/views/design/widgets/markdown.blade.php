<article>
    @if(!$design)
    {{-- 일반모드 --}}
    <div>
        {!! $content !!}
    </div>
    @else
    {{-- 디자인 모드 --}}
    <div class="card mb-3">
        <div class="card-header">
            <x-flex-between>
                <div>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-markdown" viewBox="0 0 16 16">
                            <path d="M14 3a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zM2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/>
                            <path fill-rule="evenodd" d="M9.146 8.146a.5.5 0 0 1 .708 0L11.5 9.793l1.646-1.647a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 0-.708"/>
                            <path fill-rule="evenodd" d="M11.5 5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 1 .5-.5"/>
                            <path d="M3.56 11V7.01h.056l1.428 3.239h.774l1.42-3.24h.056V11h1.073V5.001h-1.2l-1.71 3.894h-.039l-1.71-3.894H2.5V11z"/>
                        </svg>
                    </span>
                    <span>
                        {{$widget['path']}}
                    </span>
                </div>
                <div>
                    @if(!$editable)
                    <span class="text-info" wire:click="modify">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>
                    </span>
                    @endif
                </div>
            </x-flex-between>
        </div>


        @if($editable)
        {{-- 수정모드 --}}
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">파일이름</label>
                {!! xInputText()
                    ->setWire('model.defer',"forms.filename")
                    ->setWidth("standard")
                !!}
            </div>
            <div class="py-4s">
                {!! xTextarea()
                    ->setWire('model.defer',"markdown")
                !!}
            </div>
        </div>
        <div class="card-footer">
            <x-flex-between class="gap-4 mt-2">
                <div>
                    <button class="btn btn-danger btn-sm" wire:click="delete">삭제</button>
                </div>
                <div>
                    <button class="btn btn-secondary btn-sm" wire:click="cencel">취소</button>
                    <button class="btn btn-info btn-sm" wire:click="update">수정</button>
                </div>
            </x-flex-between>
        </div>
        @else
        {{-- 편집 모드 --}}
        <div class="card-body">
            <div>
                {!! $content !!}
            </div>
        </div>
        @endif
    </div>
    @endif
</article>
