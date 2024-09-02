<div>
    <x-navtab class="mb-3 nav-bordered">

        <!-- formTab -->
        <x-navtab-item class="show active" >

            <x-navtab-link class="rounded-0 active">
                <span class="d-none d-md-block">내용({{$sectionId}})</span>
            </x-navtab-link>
            <div class="p-4">
                <img src="/images{{$forms['path']}}" class="object-contain">
            </div>
            <input type="file" name="upload" wire:model.defer="upload" class="form-control"/>

        </x-navtab-item>


        <x-navtab-item >

            <x-navtab-link class="rounded-0">
                <span class="d-none d-md-block">마진,패딩</span>
            </x-navtab-link>

            <x-form-hor>
                <x-form-label>Margin</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"forms.margin")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>Padding</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"forms.padding")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>Rounding</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"forms.rounding")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

        </x-navtab-item>

        <x-navtab-item  >

            <x-navtab-link class="rounded-0">
                <span class="d-none d-md-block">크기</span>
            </x-navtab-link>

            <x-form-hor>
                <x-form-label>width</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"forms.width")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>Height</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"forms.height")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

        </x-navtab-item>

    </x-navtab>


</div>
