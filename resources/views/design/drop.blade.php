<div>

    @push('css')
    <style>
        .dropzone {
            width: 100%;
            height: 100px;
            line-height: 100px;
            text-align: center;
            color: #333;
            border: 2px dashed #bbb;
            background-color: #ccc;
        }

        .dropzone span {
            font-size: 1rem;
            text-align: center;
            font-weight: 500;
        }

        .dropzone.dragover {
            border-color:#333;
            background-color: rgb(240,240,240);
            color:#000;
        }

        /* onProgress */
        .progress-area .details{
            display: flex;
            align-items: center;
            margin-bottom: 7px;
            justify-content: space-between;
        }

        .progress-area .progress-bar{
            height: 6px;
            width: 100%;
            margin-bottom: 4px;
            background: #fff;
            border-radius: 30px;
        }

        .progress-bar .progress{
            height: 100%;
            width: 0%;
            background: #6990F2;
            border-radius: inherit;
        }

    </style>
    @endpush


    <form id="dropzone">
        @csrf
        <div class="dropzone">
            <span>생성할 페이지의 컨덴츠를 여기에 드래그 하세요.</span>
        </div>
        <div class="progress-area"></div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropzone = document.getElementById('dropzone');
            //document.querySelector(".dropzone");
            console.log(dropzone);
            if(dropzone) {
                var progressArea = dropzone.querySelector(".progress-area");
                //console.log(progressArea);

                let token = dropzone.querySelector('input[name=_token]').value;
                //console.log(token); // csrf 토큰

                dropzone.addEventListener('drop', function(e){
                    e.preventDefault();

                    let target = e.target;
                    //console.log(target);
                    while(!target.classList.contains("dropzone")) {
                        target = target.parentElement;
                    }
                    target.classList.remove("dragover");

                    console.log("drop file");
                    var files = e.dataTransfer.files;
                    //console.log(files);
                    for(let i=0; i < e.dataTransfer.files.length; i++) {
                        console.log(e.dataTransfer.files[i]);
                        uploadFile(e.dataTransfer.files[i]);
                    }
                });

                dropzone.addEventListener('dragover', function(e){
                    e.preventDefault();
                    if(dragStart) return;
                    let target = e.target;
                    while(!target.classList.contains("dropzone")) {
                        target = target.parentElement;
                    }
                    target.classList.add("dragover");

                    console.log("drag over...");
                });

                dropzone.addEventListener('dragleave', function(e){
                    e.preventDefault();
                    let target = e.target;
                    while(!target.classList.contains("dropzone")) {
                        target = target.parentElement;
                    }
                    target.classList.remove("dragover");
                });
            }



            // 파일 업로드
            function uploadFile(file) {
                var name = file.name;

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "/api/upload/404");

                let data = new FormData();
                data.append('file[]', file);
                data.append('_token', token);
                data.append('_uri', location.href);

                xhr.upload.addEventListener("progress", ({loaded, total}) =>{
                    let fileLoaded = Math.floor((loaded / total) * 100);
                    let fileTotal = Math.floor(total / 1000);
                    let fileSize;
                    (fileTotal < 1024) ? fileSize = fileTotal + " KB" : fileSize = (loaded / (1024*1024)).toFixed(2) + " MB";

                    console.log(name + "=" + fileSize);


                    let progressHTML = `<div class="details">
                            <span class="name">` + name + `</span>
                            <span class="percent">` +  " : " +fileLoaded + `%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress" style="width: `+fileLoaded +`%"></div>
                        </div>`;
                    progressArea.innerHTML = progressHTML;
                });

                xhr.onload = function() {
                    var data = JSON.parse(this.responseText);
                    console.log(data);

                    // 페이지 갱신
                    location.reload();
                }

                xhr.send(data);
            }

        });
    </script>



</div>
