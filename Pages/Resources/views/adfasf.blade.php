
                {{-- 여러개의 마크다운 파일을 출력합니다. --}}
                {{--
                @foreach ($slot as $i => $item)

                    <section class="element" data-pos="{{$i}}" data-path="{{$item->path}}" data-id="{{$item->id}}">
                        <div class="inner">

                            <article class="widget element" data-pos="{{$i}}" data-path="{{$item->path}}" data-id="{{$item->id}}">
                                @if ($item->type == "markdown")
                                    <x-markdown>
                                        {!! $item->content !!}
                                    </x-markdown>

                                @elseif ($item->type == "htm")
                                    {!! $item->content !!}

                                @elseif ($item->type == "image")
                                    <img src="/images{{$item->path}}" width="50%" alt="">

                                @elseif ($item->type == "blade")

                                    @include($item->blade)
                                @else

                                @endif
                            </article>

                        </div>
                        <div class="setting">
                            <span class="close">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                </svg>
                            </span>
                        </div>
                    </section>






                @endforeach
                --}}








