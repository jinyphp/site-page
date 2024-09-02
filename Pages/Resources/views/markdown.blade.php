<x-theme>
    {{-- 테마에 markdown.blade.php 파일이 없는 경우 이 파일로 대체 합니다. --}}
    <x-markdown>{{$slot}}</x-markdown>
</x-theme>