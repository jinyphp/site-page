<div>

    <x-form-hor>
        <x-form-label>파일명</x-form-label>
        <x-form-item>
            {!! xInputText()
                ->setWire('model.defer',"filename")
            !!}
        </x-form-item>


    </x-form-hor>

    {!! xTextarea()
        ->setWire('model.defer',"body")
        ->setAttribute("id","snow-editor")
        // 텝키 동작 허용
        ->setAttribute("onkeydown", "if(event.keyCode===9){var v=this.value,s=this.selectionStart,e=this.selectionEnd;this.value=v.substring(0, s)+'\t'+v.substring(e);this.selectionStart=this.selectionEnd=s+1;return false;}")
    !!}

    {{--
    <div id="snow-editor">
    </div>
    --}}

    <script>
    var quill = new Quill("#snow-editor", {
            theme: "snow",
            modules: {
                toolbar: [
                    [{
                        font: []
                    }, {
                        size: []
                    }],
                    ["bold", "italic", "underline", "strike"],
                    [{
                        color: []
                    }, {
                        background: []
                    }],
                    [{
                        script: "super"
                    }, {
                        script: "sub"
                    }],
                    [{
                        header: [!1, 1, 2, 3, 4, 5, 6]
                    }, "blockquote", "code-block"],
                    [{
                        list: "ordered"
                    }, {
                        list: "bullet"
                    }, {
                        indent: "-1"
                    }, {
                        indent: "+1"
                    }],
                    ["direction", {
                        align: []
                    }],
                    ["link", "image", "video"],
                    ["clean"]
                ]
            }
    });
    </script>



</div>


