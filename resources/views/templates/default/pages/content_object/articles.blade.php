@extends($templatePath.'layouts.main')
@section('content')

    <section id="main-container" class="main-container">
        <div class="container">
            <div class="row">

                <div class="col-lg-8 mb-5 mb-lg-0">

                    <div class="post-content post-single">


                        <div class="post-body">
                            <div class="entry-header">
                                <h2 class="entry-title">
                                    {{ $model->getName() }}
                                </h2>
                            </div><!-- header end -->

                            <div class="entry-content">
                               {!! $model->field('b1fccafef4e3') !!}
                            </div>


                        </div><!-- post-body end -->
                    </div><!-- post content end -->


                </div><!-- Content Col end -->

                <div class="col-lg-4">

                    @includeIf($templatePath.'pages.content_object.sidebar.'.$sidebar->key)
                </div><!-- Sidebar Col end -->

            </div><!-- Main row end -->

        </div><!-- Conatiner end -->
    </section><!-- Main container end -->
@endsection
