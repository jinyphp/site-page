<div>

    <x-navtab class="mb-3 nav-bordered">

        <!-- formTab -->
        <x-navtab-item class="show active" >

            <x-navtab-link class="rounded-0 active">
                <span class="d-none d-md-block">기본정보</span>
            </x-navtab-link>

            <x-form-hor>
                <x-form-label>resource path</x-form-label>
                <x-form-item>
                    @php
                        if(!isset($form['path'])) {
                            // 기본값
                            $form['path'] = "actions";
                        }
                    @endphp
                    {!! xInputText()
                        ->setWire('model.defer',"forms.path")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>menu</x-form-label>
                <x-form-item>

                    {!! xInputText()
                        ->setWire('model.defer',"forms.menu")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>Auth</x-form-label>
                <x-form-item>

                    {!! xInputText()
                        ->setWire('model.defer',"forms.auth")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>



        </x-navtab-item>





        <!-- formTab -->
        <x-navtab-item >
            <x-navtab-link class="rounded-0">
                <span class="d-none d-md-block">메모</span>
            </x-navtab-link>

            <x-form-hor>
                <x-form-label>메모</x-form-label>
                <x-form-item>
                    {!! xTextarea()
                        ->setWire('model.defer',"forms.description")
                    !!}
                </x-form-item>
            </x-form-hor>

        </x-navtab-item>

    </x-navtab>
</div>
