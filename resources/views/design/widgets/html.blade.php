<article>
    @if(!$design)
    {{-- 일반모드 --}}
    <div>
        {!! $html !!}
    </div>
    @else
    {{-- 디자인 모드 --}}
    <div class="card mb-3">
        <div class="card-header">
            <x-flex-between>
                <div>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-code-slash" viewBox="0 0 16 16">
                            <path d="M10.478 1.647a.5.5 0 1 0-.956-.294l-4 13a.5.5 0 0 0 .956.294zM4.854 4.146a.5.5 0 0 1 0 .708L1.707 8l3.147 3.146a.5.5 0 0 1-.708.708l-3.5-3.5a.5.5 0 0 1 0-.708l3.5-3.5a.5.5 0 0 1 .708 0m6.292 0a.5.5 0 0 0 0 .708L14.293 8l-3.147 3.146a.5.5 0 0 0 .708.708l3.5-3.5a.5.5 0 0 0 0-.708l-3.5-3.5a.5.5 0 0 0-.708 0"/>
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

                <div>
                    {!! xTextarea()
                        ->setWire('model.defer',"forms.blade")
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
            {!! $html !!}
        </div>
        @endif
    </div>
@endif


</article>
