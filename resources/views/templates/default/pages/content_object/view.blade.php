@extends($templatePath.'layouts.main')
@section('content')
    <section id="main-container" class="main-container">
        <div class="container">
            <div class="row">
                @if($sidebar->key == 'project-sidebar-left')
                    <div class="col-lg-4">
                        @includeIf($templatePath.'pages.content_object.sidebar.'.$sidebar->key)
                    </div><!-- Sidebar Col end -->
                @endif
                <div class="col-lg-8 mb-5 mb-lg-0">

                    <div class="post-content post-single">
                        <div class="post-media post-image">
                            <img loading="lazy" src="{{ $model->getFirstMediaUrl('gallery') }}"
                                 class="img-fluid" alt="post-image">
                        </div>

                        <div class="post-body">
                            <div class="entry-header">
                                <div class="post-meta">
                <span class="post-author">
                  <i class="far fa-user"></i><a href="#"> Admin</a>
                </span>
                                    <span class="post-cat">
                  <i class="far fa-folder-open"></i><a href="#"> News</a>
                </span>
                                    <span class="post-meta-date"><i class="far fa-calendar"></i> June 14, 2016</span>
                                    <span class="post-comment"><i class="far fa-comment"></i> 03<a href="#"
                                                                                                   class="comments-link">Comments</a></span>
                                </div>
                                <h2 class="entry-title">
                                    {{ $model->field('b8cabbeab076') }}
                                </h2>
                            </div><!-- header end -->

                            <div class="entry-content">
                                {!! $model->field('64aac0ef50f0') !!}
                            </div>

                            <div class="tags-area d-flex align-items-center justify-content-between">
                                <div class="post-tags">
                                    <a href="#">Construction</a>
                                    <a href="#">Safety</a>
                                    <a href="#">Planning</a>
                                </div>
                                <div class="share-items">
                                    <ul class="post-social-icons list-unstyled">
                                        <li class="social-icons-head">Share:</li>
                                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fab fa-google-plus"></i></a></li>
                                        <li><a href="#"><i class="fab fa-linkedin"></i></a></li>
                                    </ul>
                                </div>
                            </div>

                        </div><!-- post-body end -->
                    </div><!-- post content end -->


                </div><!-- Content Col end -->

                @if($sidebar->key == 'project-sidebar-right')
                    <div class="col-lg-4">
                        @includeIf($templatePath.'pages.content_object.sidebar.'.$sidebar->key)
                    </div><!-- Sidebar Col end -->
                @endif

                @if($sidebar->key != 'project-sidebar-right' && $sidebar->key != 'project-sidebar-left')
                    <div class="col-lg-4">
                        @includeIf($templatePath.'pages.content_object.sidebar.'.$sidebar->key)
                    </div>
                @endif


            </div><!-- Main row end -->

        </div><!-- Conatiner end -->
    </section><!-- Main container end -->

@endsection
