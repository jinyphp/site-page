<article>
    @if(!$design)
        {{-- 일반모드 --}}
        @includeIf($widget['view']['list'])
    @else
    {{-- 디자인 모드 --}}
        @includeIf($widget['view']['design'])
    @endif
</article>
