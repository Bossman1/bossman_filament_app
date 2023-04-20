@extends($templatePath.'layouts.main')
@section('content')
    <section id="main-container" class="main-container">
        <div class="container">
            <div class="row">

                <div class="col-lg-8 mb-5 mb-lg-0">

                    @if($children->isNotEmpty())
                        @foreach($children as $child)

                            <div class="post">
                                @if($child->getFirstMediaUrl('gallery'))
                                <div class="post-media post-image">
                                    <img loading="lazy" src="{{ $child->getFirstMediaUrl('gallery')}}" class="img-fluid" alt="post-image">
                                </div>
                                @endif

                                <div class="post-body">
                                    <div class="entry-header">
                                        <div class="post-meta">
                                            <span class="post-meta-date"><i class="far fa-calendar"></i> {{ \Carbon\Carbon::make($child->created_at)->format('F d,Y') }}</span>
                                        </div>
                                        <h2 class="entry-title">
                                            <a href="{{ route('content.view',$child->slug) }}">{{ $child->getName() }}</a>
                                        </h2>
                                    </div><!-- header end -->

                                    <div class="entry-content">{!! $child->field('b1fccafef4e3') !!}</div>

                                    <div class="post-footer">
                                        <a href="{{ route('content.view',$child->slug) }}" class="btn btn-primary">Continue Reading</a>
                                    </div>

                                </div><!-- post-body end -->
                            </div><!-- 1st post end -->
                        @endforeach





                        @include($templatePath.'includes.pagination',['model'=>$children])
                    @endif
                </div><!-- Content Col end -->

                <div class="col-lg-4">
                    @includeIf($templatePath.'pages.content_object.sidebar.'.$sidebar->key)
                </div><!-- Sidebar Col end -->

            </div><!-- Main row end -->

        </div><!-- Container end -->
    </section><!-- Main container end -->
@endsection
