<div id="carousel-{{$sliders['code']}}" class="carousel slide">

    <div class="carousel-inner">
    @foreach($sliders['items'] as $item)
        @if($loop->first)
        <div class="carousel-item active">
        @else
        <div class="carousel-item">
        @endif
        <div class="row">
            <div class="col-6">
                <h2>{{$item['title']}}</h2>
            </div>
            <div class="col-6">
                <img src="{{$item['image']}}"
                    class="d-block w-100"
                    alt="{{$item['title']}}">
            </div>
        </div>
      </div>
    @endforeach
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{$sliders['code']}}" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{$sliders['code']}}" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
</div>
