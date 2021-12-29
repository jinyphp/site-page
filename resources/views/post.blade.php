{{-- 목록을 출력하기 위한 템플릿 --}}
<x-theme>
    <x-theme-layout>

        <x-markdown>
            {!! $slot !!}
        </x-markdown>

        {{-- Admin Rule Setting --}}
        @include('jinypage::setPostRule')

    </x-theme-layout>
</x-theme>
