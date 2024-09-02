<x-theme theme="admin.sidebar">
    <x-theme-layout>

        <!-- Module Title Bar -->
        @if(Module::has('Titlebar'))
            @livewire('TitleBar', ['actions'=>$actions])
        @endif
        <!-- end -->

        <style>
            .directory ul {
                padding-left: 15px;
            }

            .directory li {
                padding: 10px 0px 0px 10px;
                border-left-color: gray;
                border-left-width: 1px;
                margin-top: -1px;
                border-top-color: #cccccc;
                border-top-width: 1px;
                border-top-style: dashed;
            }
        </style>

        <x-card>
            <x-card-body>

                {{-- Live 디렉터리를 출력합니다. --}}
                @if(Module::has('Files'))
                    @livewire('FileExplore', ['actions' => $actions, 'path' => $actions['path'] ])
                @else
                    <p>관리자 페이지에서 Files 모듈을 먼저 설치해 주세요.</p>
                @endif

            </x-card-body>
        </x-card>

        <!-- dropzone -->
        @include("files::script.drop")


        {{-- Admin Rule Setting --}}
        @include('files::setActionRule')

        {{-- 파일정보 수정 --}}

        @livewire('popupJson', ['actions' => $actions])



    </x-theme-layout>
</x-theme>
