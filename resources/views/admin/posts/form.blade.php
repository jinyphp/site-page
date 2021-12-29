<div>

    <x-navtab class="mb-3 nav-bordered">

        <!-- formTab -->
        <x-navtab-item class="show active" >

            <x-navtab-link class="rounded-0 active">
                <span class="d-none d-md-block">기본정보</span>
            </x-navtab-link>

            <x-form-hor>
                <x-form-label>활성화</x-form-label>
                <x-form-item>
                    {!! xCheckbox()
                        ->setWire('model.defer',"form.enable")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>제목</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"form.title")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>포스트</x-form-label>
                <x-form-item>
                    {!! xTextarea()
                        ->setWire('model.defer',"form.content")
                    !!}
                </x-form-item>
            </x-form-hor>

        </x-navtab-item>

        <!-- formTab -->
        <x-navtab-item >
            <x-navtab-link class="rounded-0">
                <span class="d-none d-md-block">SEO</span>
            </x-navtab-link>

            <x-form-hor>
                <x-form-label>제목</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"form.seo_title")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>키워드</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"form.seo_keyword")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>내용</x-form-label>
                <x-form-item>
                    {!! xTextarea()
                        ->setWire('model.defer',"form.seo_description")
                    !!}
                </x-form-item>
            </x-form-hor>

        </x-navtab-item>

        <!-- formTab -->
        <x-navtab-item >
            <x-navtab-link class="rounded-0">
                <span class="d-none d-md-block">배포</span>
            </x-navtab-link>

            <x-form-hor>
                <x-form-label>배포일자</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"form.publish")
                    !!}
                </x-form-item>
            </x-form-hor>

            <x-form-hor>
                <x-form-label>담당자</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"form.manager")
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
                        ->setWire('model.defer',"form.description")
                    !!}
                </x-form-item>
            </x-form-hor>

        </x-navtab-item>

    </x-navtab>

</div>
