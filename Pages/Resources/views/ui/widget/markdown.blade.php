<div >
    <x-navtab class="mb-3 nav-bordered">

        <!-- formTab -->
        <x-navtab-item class="show active" >

            <x-navtab-link class="rounded-0 active">
                <span class="d-none d-md-block">내용({{$id}})</span>
            </x-navtab-link>


            @if ($row->type == "image")

                <input type="file" name="upload" wire:model.defer="upload" class="form-control"/>

            @else
                {!! xTextarea()
                    ->setName("content")
                    ->setWire('model.defer',"content")
                    ->setId("editor")
                !!}
            @endif




        </x-navtab-item>

        <!-- formTab -->
        <x-navtab-item >
            <x-navtab-link class="rounded-0">
                <span class="d-none d-md-block">설정</span>
            </x-navtab-link>

            <x-form-hor>
                <x-form-label>Margin</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setName("margin")
                        ->setWire('model.defer',"margin")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>Padding</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setName("padding")
                        ->setWire('model.defer',"padding")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>width</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setName("width")
                        ->setWire('model.defer',"width")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>Height</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setName("height")
                        ->setWire('model.defer',"height")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

        </x-navtab-item>

    </x-navtab>


</div>
