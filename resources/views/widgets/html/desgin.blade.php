<article>

    @if (!$design)
        {{-- 일반모드 --}}
        <div class="mb-4">
            {!! clean($html) !!}
        </div>
    @else
        {{-- 디자인 모드 --}}
        @if ($editable)
            {{-- 수정모드 --}}
            <div class="mb-3">
                {!! xTextarea()->setWire('model.defer', 'forms.blade') !!}

                <x-flex-between class="gap-4 mt-2">
                    <div class="d-flex gap-2">
                        {!! xInputText()->setWire('model.defer', 'forms.filename')->setWidth('standard') !!}
                        <button class="btn btn-danger btn-sm" wire:click="remove('{{$widget['key']}}')">삭제</button>

                    </div>
                    <div>
                        <button class="btn btn-secondary btn-sm" wire:click="cencel">취소</button>
                        <button class="btn btn-info btn-sm" wire:click="update">수정</button>
                    </div>
                </x-flex-between>
            </div>
        @else
            {{-- 편집 모드 --}}
            <div wire:click="modify" class="hover:bg-gray-100">
                {!! clean($html) !!}
            </div>
        @endif
        </div>
    @endif
</article>
