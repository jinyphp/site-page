<article>
    @if (!$design)

            @includeIf($widget['view']['list'])

    @else
        @if ($editable)
            @includeIf($widget['view']['form'])

            @if (is_numeric($_id))
                <x-flex-between class="gap-4 mt-2">
                    <div>
                        <button class="btn btn-danger btn-sm" wire:click="delete">이미지 삭제</button>
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
        @else
            <div class="hover:bg-gray-100">
                <div class="d-flex gap-2 justify-content-center">
                    @foreach ($images as $i => $img)
                        <div>
                            @if (isset($forms[$i]))
                                {{ $forms[$i] }}
                            @endif
                            <x-click wire:click="edit({{ $i }})">
                                <img src="{{ $img }}" alt="">
                            </x-click>
                        </div>
                    @endforeach

                    <div class="d-flex @if(count($images)) flex-column @endif justify-content-between align-items-center px-2 gap-2">
                        <span wire:click="create">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                            </svg>
                        </span>
                        <button class="btn btn-danger btn-sm" wire:click="remove('{{$widget['key']}}')">
                            위젯 삭제
                        </button>
                    </div>
                </div>

            </div>
        @endif
    @endif
</article>
