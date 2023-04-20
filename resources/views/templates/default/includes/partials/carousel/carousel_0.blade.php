<div class="banner-carousel-item" style="background-image:url({{ $modelTopSlide->getFirstMediaUrl('gallery') }})">
    <div class="slider-content">
        <div class="container h-100">
            <div class="row align-items-center h-100">
                <div class="col-md-12 text-center">
                    <h2 class="slide-title"
                        data-animation-in="slideInLeft">{{ $modelTopSlide->field('d996ff725193') }}</h2>
                    <h3 class="slide-sub-title"
                        data-animation-in="slideInRight">{{ $modelTopSlide->field('ef839cc8da6c') }}</h3>
                    <p data-animation-in="slideInLeft" data-duration-in="1.2">
                        @if($modelTopSlide->field('0d79ded9d10d') != '')
                            <a href="{{$modelTopSlide->field('65acbea3118c') }}" target="_blank"
                               class="slider btn btn-primary">{{ $modelTopSlide->field('0d79ded9d10d') }}</a>
                        @endif
                        @if($modelTopSlide->field('e8bb1b8be0d8') != '')
                            <a href="{{$modelTopSlide->field('d9d57ed811e1') }}" target="_blank"
                               class="slider btn btn-primary border">{{ $modelTopSlide->field('e8bb1b8be0d8') }}</a>
                        @endif

                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
