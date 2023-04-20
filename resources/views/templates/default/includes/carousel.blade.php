<div class="banner-carousel banner-carousel-1 mb-0">
    @foreach($modelTopSliders->children()->orderBy('sort')->get() as $modelTopSlide)
        @include($templatePath.'includes.partials.carousel.carousel_'.$modelTopSlide->field('88a54e1ab702'))
    @endforeach
</div>
