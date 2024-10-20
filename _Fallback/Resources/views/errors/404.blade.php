<x-theme>
    <x-container>
        <h1 class="p-8 text-center text-9xl">404</h1>
        <p class="text-center">요청한 페이지를 찾을 수 없습니다.</p>

        <hr>

        <x-row>
            <x-col-4>
                @livewire('AddPopupPage',['uri'=>$_SERVER['PATH_INFO']])
            </x-col-4>
            <x-col-4>
                <x-card class="h-100">
                    <x-card-body>
                        입력폼
                    </x-card-body>
                </x-card>
            </x-col-4>
            <x-col-4>
                <x-card class="h-100">
                    <x-card-body>
                        테이블
                    </x-card-body>
                </x-card>
            </x-col-4>
        </x-row>

        <hr>

        @livewire('Drop404',['uri'=>$_SERVER['PATH_INFO']])

    </x-container>
</x-theme>
