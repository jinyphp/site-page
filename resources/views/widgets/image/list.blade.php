<div class="d-flex gap-1 justify-content-between w-100">
    @foreach( $images as $img)
    <div>
        <img src="{{$img}}" alt="">
    </div>
    @endforeach
</div>
