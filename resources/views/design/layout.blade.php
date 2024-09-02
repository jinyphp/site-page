<x-www-app>
    <x-www-layout>

        <div class="container">

            @includeIf('jiny-site-page::design.script')

            {{-- dropzone --}}
            @includeIf('jiny-site-page::design.dropzone')

            {{-- Admin Rule Setting --}}
            @include('jiny-site-page::setMarkRule')

            @includeIf('jiny-site-page::design.widgets')

        </div>

    </x-www-layout>
</x-www-app>
{{-- <x-theme name="admin.sidebar">
    <x-theme-layout>



    </x-theme-layout>
</x-theme> --}}
