@extends($templatePath.'layouts.main')
@section('content')
    <section id="main-container" class="main-container">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h3 class="column-title">{{ $model->field('d37f7bdaec33') }}</h3>
                  {!! $model->field('7ea17fca2a29') !!}
                </div><!-- Col end -->

                <div class="col-lg-6 mt-5 mt-lg-0">

                    <div id="page-slider" class="page-slider small-bg">

                        @foreach($model->getMedia('gallery') as $media)
                            <div class="item" style="background-image:url({{ $media->getUrl() }})">

                            </div><!-- Item 1 end -->
                        @endforeach

                    </div><!-- Page slider end-->


                </div><!-- Col end -->
            </div><!-- Content row end -->

        </div><!-- Container end -->
    </section><!-- Main container end -->


    <section id="facts" class="facts-area dark-bg">
        <div class="container">
            <div class="facts-wrapper">
                <div class="row">

                    @if($modelHomePageCounter->children->isNotEmpty())
                        @foreach($modelHomePageCounter->children()->orderBy('sort')->get() as $children)
                            <div
                                class="col-md-3 col-sm-6 @if($loop->index == 0) ts-facts @else ts-facts  mt-5 mt-sm-0 @endif">
                                <div class="ts-facts-img">
                                    <img loading="lazy" src="{!! $children->getFirstMediaUrl('gallery') !!}"
                                         alt="facts-img">
                                </div>
                                <div class="ts-facts-content">
                                    <h2 class="ts-facts-num"><span class="counterUp"
                                                                   data-count="{!! $children->field('3a16ba09b5e9') !!}">0</span>
                                    </h2>
                                    <h3 class="ts-facts-title">{!! $children->field('a677d7c55141') !!}</h3>
                                </div>
                            </div><!-- Col end -->
                        @endforeach
                    @endif

                </div> <!-- Facts end -->
            </div>
            <!--/ Content row end -->
        </div>
        <!--/ Container end -->
    </section><!-- Facts end -->


    @includeIf('pages.content_object.relations.relation')
@endsection
