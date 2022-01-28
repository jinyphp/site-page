
<style>
    .off-side-panel {
        border-left: 1px solid #e4e4e4;
        background-color: #f5f5f5;;
        height: 100%;
    }

    .panel-item {
        background-color: #f5f5f5;
        color: #b3b3b3;

        border-bottom: 2px solid #e4e4e4;

    }
</style>
<div class="off-side-panel">
    section side pannel

    <form action="/api/pages/pannel/section" method="POST">
        @csrf
        @method('POST')

        <input type="hidden" name="_id" value="{{$id}}">

        <div class="panel-item">
            <div >
                {{$id}} Article Widget 설정
            </div>

            <div class="flex">
                <div width="180px">
                    <label for="">padding :</label>
                </div>
                <div class="flex-grow">
                    <input type="text" name="padding" value="{{$row->padding}}" width="100%">
                </div>
            </div>
            <div class="flex">
                <div width="180px">
                    <label for="">margin :</label>
                </div>
                <div class="flex-grow">
                    <input type="text" name="margin" value="{{$row->margin}}" width="100%">
                </div>
            </div>

            <div class="flex">
                <div width="180px">
                    <label for="">width :</label>
                </div>
                <div class="flex-grow">
                    <input type="text" name="width" value="{{$row->width}}" width="100%">
                </div>
            </div>

            <div class="flex">
                <div width="180px">
                    <label for="">Height :</label>
                </div>
                <div class="flex-grow">
                    <input type="text" name="height" value="{{$row->height}}" width="100%">
                </div>
            </div>

        </div>


        <div class="panel-item">
            <div>
                Inner 설정
            </div>
            <div class="flex">
                <div width="180px">
                    <label for="">간격</label>
                </div>
                <div class="flex-grow">

                </div>
            </div>

        </div>

        <button type="submit">설정</button>
    </form>
</div>
