{{-- 목록을 출력하기 위한 템플릿 --}}
<x-theme>
    <x-theme-layout>

        @if (isset($actions['view_content']))
            @includeIf($actions['view_content'])
        @endif

        {{-- Admin Rule Setting --}}
        @include('jiny-site-page::setPageRule')

    </x-theme-layout>
</x-theme>
