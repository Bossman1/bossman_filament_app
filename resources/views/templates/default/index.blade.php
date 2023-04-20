@extends($templatePath.'layouts.main')
@section('content')

    @include($templatePath.'includes.carousel')

    <section class="call-to-action-box no-padding">
        <div class="container">
            <div class="action-style-box">
                <div class="row align-items-center">
                    <div class="col-md-8 text-center text-md-left">
                        <div class="call-to-action-text">
                            {!! $modelTextBlock1->field('afdd66713761') !!}
                        </div>
                    </div><!-- Col end -->
                    <div class="col-md-4 text-center text-md-right mt-3 mt-md-0">
                        <div class="call-to-action-btn">
                            <a class="btn" target="_blank"
                               style="background: {{ $modelTextBlock1->field('c60277a28b23') }}; "
                               href="{{ $modelTextBlock1->field('501caa1fe3d2') }}">{{ $modelTextBlock1->field('4424ceef4210') }}</a>
                        </div>
                    </div><!-- col end -->
                </div><!-- row end -->
            </div><!-- Action style box -->
        </div><!-- Container end -->
    </section><!-- Action end -->

    <section id="ts-features" class="ts-features">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="ts-intro">
                        <h2 class="into-title">{{ $modelAboutUs->field('0f52b4175deb') }}</h2>
                        <h3 class="into-sub-title">{{ $modelAboutUs->field('712fc9e05845') }}</h3>
                        {!! $modelAboutUs->field('a8578b2c4052') !!}
                    </div><!-- Intro box end -->

                    <div class="gap-20"></div>
                    @if($modelAboutUs->children->isNotEmpty())

                        @foreach($modelAboutUs->children()->orderBy('sort')->get() as $children)
                            @if($loop->odd)
                                <div class="row">  @endif

                                    <div class="col-md-6">
                                        <div class="ts-service-box">
                                        <span class="ts-service-icon">
                                           {!! $children->field('b1fccafef4e3') !!}
                                        </span>
                                            <div class="ts-service-box-content">
                                                <h3 class="service-box-title"> {{ $children->field('11f8bc158ef2') }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    @if($loop->even)</div>
                            @endif

                        @endforeach

                    @endif


                </div><!-- Col end -->


                <div class="col-lg-6 mt-4 mt-lg-0">
                    <h3 class="into-sub-title">{{ $modelOurValues->field('11f8bc158ef2') }}</h3>
                    {!! $modelOurValues->field('f069bea5a7a5') !!}

                    <div class="accordion accordion-group" id="our-values-accordion">

                        @if($modelOurValues->children->isNotEmpty())
                            @foreach($modelOurValues->children()->orderBy('sort')->get() as $children)

                                <div class="card">
                                    <div class="card-header p-0 bg-transparent" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn btn-block text-left" type="button"
                                                    data-toggle="collapse"
                                                    data-target="#collapse_{{ $loop->index }}"
                                                    aria-expanded="@if($loop->index == 0) true @endif"
                                                    aria-controls="collapse_{{ $loop->index }}">
                                                {{ $children->field('11f8bc158ef2') }}
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapse_{{ $loop->index }}"
                                         class="collapse @if($loop->index == 0) show @endif"
                                         aria-labelledby="headingOne" data-parent="#our-values-accordion">
                                        <div class="card-body">{!! $children->field('f069bea5a7a5') !!}</div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                    </div>
                    <!--/ Accordion end -->

                </div><!-- Col end -->
            </div><!-- Row end -->
        </div><!-- Container end -->
    </section><!-- Feature are end -->

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

    <section id="ts-service-area" class="ts-service-area pb-0">
        <div class="container">
            <div class="row text-center">
                <div class="col-12">
                    <h2 class="section-title">{{ $modelWhatWeDo->field('11f8bc158ef2') }}</h2>
                    <h3 class="section-sub-title">{{ $modelWhatWeDo->field('b1fccafef4e3') }}</h3>
                </div>
            </div>
            <!--/ Title row end -->

            <div class="row">


                @if($modelWhatWeDo->children->isNotEmpty())

                    @foreach($modelWhatWeDo->children()->orderBy('sort')->get() as $children)

                        @if($children->slug == 'left-column')
                            <div class="col-lg-4">
                                @foreach($children->children()->orderBy('sort')->get() as $ch)
                                    <div class="ts-service-box d-flex">
                                        <div class="ts-service-box-img">
                                            <img loading="lazy" src="{{ $ch->getFirstMediaUrl('gallery') }}"
                                                 alt="service-icon">
                                        </div>
                                        <div class="ts-service-box-info">
                                            <h3 class="service-box-title"><a
                                                    href="#">{{ $ch->field('657f3f2f78a1') }}</a></h3>
                                            <p>{{ $ch->field('4904d7ee7d72') }}</p>
                                        </div>
                                    </div><!-- Service 1 end -->
                                @endforeach


                            </div><!-- Col end -->
                        @endif

                        @if($children->slug == 'center-image')
                            <div class="col-lg-4 text-center">
                                <img loading="lazy" class="img-fluid" src="{{ $children->getFirstMediaUrl('gallery') }}"
                                     alt="service-avater-image">
                            </div><!-- Col end -->
                        @endif

                        @if($children->slug == 'right-column')
                            <div class="col-lg-4 mt-5 mt-lg-0 mb-4 mb-lg-0">

                                @foreach($children->children()->orderBy('sort')->get() as $ch)
                                    <div class="ts-service-box d-flex">
                                        <div class="ts-service-box-img">
                                            <img loading="lazy" src="{{ $ch->getFirstMediaUrl('gallery') }}"
                                                 alt="service-icon">
                                        </div>
                                        <div class="ts-service-box-info">
                                            <h3 class="service-box-title"><a
                                                    href="#">{{ $ch->field('657f3f2f78a1') }}</a></h3>
                                            <p>{{ $ch->field('4904d7ee7d72') }}</p>
                                        </div>
                                    </div><!-- Service 4 end -->
                                @endforeach

                            </div><!-- Col end -->
                        @endif

                    @endforeach

                @endif

            </div><!-- Content row end -->

        </div>
        <!--/ Container end -->
    </section><!-- Service end -->

    <section id="project-area" class="project-area solid-bg">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-12">
                    <h2 class="section-title">{{ $modelWorkOfExcellent->field('11f8bc158ef2') }}</h2>
                    <h3 class="section-sub-title">{{ $modelWorkOfExcellent->field('b1fccafef4e3') }}</h3>
                </div>
            </div>
            <!--/ Title row end -->

            <div class="row">
                <div class="col-12">
                    <div class="shuffle-btn-group">
                        <label class="active" for="all">
                            <input type="radio" name="shuffle-filter" id="all" value="all" checked="checked">Show All
                        </label>

                        @if(!empty($modelMenu1->content))
                            @foreach($modelMenu1->content as $menu)

                                <label for="{{ strtolower($menu['menu_list']) }}">
                                    <input type="radio" name="shuffle-filter" id="{{ strtolower($menu['menu_list']) }}"
                                           value="{{ strtolower($menu['menu_list']) }}">{{ $menu['menu_list'] }}
                                </label>
                            @endforeach
                        @endif

                    </div><!-- project filter end -->


                    <div class="row shuffle-wrapper">
                        <div class="col-1 shuffle-sizer"></div>

                        @if($modelWorkOfExcellent->children->isNotEmpty())
                            @foreach($modelWorkOfExcellent->children()->orderBy('sort')->get() as $children)
                                    <?php $menuArray = $modelMenu1->getMenuByArray($children->field('51e287afc017'));

                                    $menuCategories = implode(
                                        ",",
                                        array_map(
                                            function ($menuArray) {
                                                return ('"' . $menuArray . '"');
                                            },
                                            $menuArray
                                        )
                                    );

                                    ?>


                                <div class="col-lg-4 col-md-6 shuffle-item"
                                     data-groups='[{{ $menuCategories }}]'>
                                    <div class="project-img-container">
                                        <a class="gallery-popup" href="{{ $children->getFirstMediaUrl('gallery') }}"
                                           aria-label="project-img">
                                            <img class="img-fluid" src="{{ $children->getFirstMediaUrl('gallery') }}"
                                                 alt="project-img">
                                            <span class="gallery-icon"><i class="fa fa-plus"></i></span>
                                        </a>
                                        <div class="project-item-info">
                                            <div class="project-item-info-content">
                                                <h3 class="project-item-title">
                                                    <a href="">Capital Teltway Building</a>
                                                </h3>
                                                <p class="project-cat">Commercial, Interiors</p>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- shuffle item 1 end -->

                            @endforeach
                        @endif

                    </div>

                    <div class="col-12">
                        <div class="general-btn text-center">
                            <a class="btn btn-primary" href="/projects">View All Projects</a>
                        </div>
                    </div>

                </div><!-- Content row end -->
            </div>
            <!--/ Container end -->
    </section><!-- Project area end -->

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h3 class="column-title">Testimonials</h3>

                    <div id="testimonial-slide" class="testimonial-slide">
                        @if($modelTestimonials->children->isNotEmpty())
                            @foreach($modelTestimonials->children()->orderBy('sort')->get() as $children)

                                <div class="item">
                                    <div class="quote-item">
                                        <span class="quote-text">{!! $children->field('64aac0ef50f0') !!}</span>
                                        <div class="quote-item-footer">
                                            <img loading="lazy" class="testimonial-thumb"
                                                 src="{{ $children->getFirstMediaUrl('gallery') }}"
                                                 alt="testimonial">
                                            <div class="quote-item-info">
                                                <h3 class="quote-author">{!! $children->field('b8cabbeab076') !!}</h3>
                                                <span
                                                    class="quote-subtext">{{ $children->field('6a9405853f40') }}</span>
                                            </div>
                                        </div>
                                    </div><!-- Quote item end -->
                                </div>

                            @endforeach
                        @endif

                    </div>
                    <!--/ Testimonial carousel end-->
                </div><!-- Col end -->

                <div class="col-lg-6 mt-5 mt-lg-0">

                    <h3 class="column-title">Happy Clients</h3>

                    <div class="row all-clients">

                        @if($modelhappyClients->children->isNotEmpty())
                            @foreach($modelhappyClients->children()->orderBy('sort')->get() as $children)
                                <div class="col-sm-4 col-6">
                                    <figure class="clients-logo">
                                        <a href="{{ $children->field('396d54b03691') }}"><img loading="lazy"
                                                                                              class="img-fluid"
                                                                                              src="{{ $children->getFirstMediaUrl('gallery') }}"
                                                                                              alt="clients-logo"/></a>
                                    </figure>
                                </div><!-- Client 1 end -->

                            @endforeach
                        @endif
                    </div><!-- Clients row end -->

                </div><!-- Col end -->

            </div>
            <!--/ Content row end -->
        </div>
        <!--/ Container end -->
    </section><!-- Content end -->

    <section class="subscribe no-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="subscribe-call-to-acton">
                        <h3>{{ $modelSubscribeBlock->field('21595459b226') }}</h3>
                        <h4>{{ $modelSubscribeBlock->field('4aa17e187ce7') }}</h4>
                    </div>
                </div><!-- Col end -->

                <div class="col-lg-8">
                    <div class="ts-newsletter row align-items-center">
                        <div class="col-md-5 newsletter-introtext">
                            <h4 class="text-white mb-0">{{ $modelSubscribeBlock->field('3e7770d5d52a') }}</h4>
                            <p class="text-white">{{ $modelSubscribeBlock->field('1e341b52c46f') }}</p>
                        </div>

                        <div class="col-md-7 newsletter-form">
                            <form action="#" method="post">
                                <div class="form-group">
                                    <label for="newsletter-email" class="content-hidden">Newsletter Email</label>
                                    {!! \BossmanFilamentApp\Http\Controllers\FormsController::form($modelSubscribeBlock, 'd113d13aec8d|email',['id'=>'newsletter-email','class'=>'form-control form-control-lg','label'=>false,'attributes' => ['placeholder'=>'test placeholder']]) !!}
                                </div>
                            </form>
                        </div>
                    </div><!-- Newsletter end -->
                </div><!-- Col end -->

            </div><!-- Content row end -->
        </div>
        <!--/ Container end -->
    </section>
    <!--/ subscribe end -->

    <section id="news" class="news">
        <div class="container">
            <div class="row text-center">
                <div class="col-12">
                    <h2 class="section-title">Work of Excellence</h2>
                    <h3 class="section-sub-title">Recent Projects</h3>
                </div>
            </div>
            <!--/ Title row end -->

            <div class="row">
                @if($modelProjects->children->isNotEmpty())

                    @foreach($modelProjects->children()->orderBy('sort')->orderBy('created_at', 'asc')->limit(3)->get() as $children)

                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="latest-post">
                                <div class="latest-post-media">
                                    <a href="{{ route('content.view',$children->slug) }}" class="latest-post-img">
                                        <img loading="lazy" class="img-fluid" src="{{ $children->getFirstMediaUrl('gallery') }}" alt="img">
                                    </a>
                                </div>
                                <div class="post-body">
                                    <h4 class="post-title">
                                        <a href="{{ route('content.view',$children->slug) }}" class="d-inline-block"> {{ $children->field('b8cabbeab076') }} </a>
                                    </h4>
                                    <div class="latest-post-meta">
                                        <span class="post-item-date">
                                          <i class="fa fa-clock-o"></i>   {{ $children->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div><!-- Latest post end -->
                        </div><!-- 1st post col end -->
                    @endforeach
                @endif

            </div>
            <!--/ Content row end -->

            <div class="general-btn text-center mt-4">
                <a class="btn btn-primary" href="{{ route('content.list',$modelProjects->slug) }}">See All Posts</a>
            </div>

        </div>
        <!--/ Container end -->
    </section>

@endsection
