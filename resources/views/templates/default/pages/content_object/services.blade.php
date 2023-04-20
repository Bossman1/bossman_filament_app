@extends($templatePath.'layouts.main')
@section('content')
    <section id="main-container" class="main-container">
        <div class="container">
            <div class="row">

                <div class="col-xl-3 col-lg-4">
                    @includeIf($templatePath.'pages.content_object.sidebar.'.$sidebar->key)
                </div><!-- Sidebar Col end -->

                <div class="col-xl-8 col-lg-8">
                    <div class="content-inner-page">

                        <h2 class="column-title mrt-0">{{ $model->field('58abe5b1b9f6') }}</h2>

                        <div class="row">
                            <div class="col-md-12">{!! $model->field('d1e850fad9a5')  !!}</div><!-- col end -->
                        </div><!-- 1st row end-->

                        <div class="gap-40"></div>

                        @if($model->getMedia('gallery'))
                            <div id="page-slider" class="page-slider">
                                @foreach($model->getMedia('gallery') as $media)

                                    <div class="item">
                                        <img loading="lazy" class="img-fluid" src="{{ $media->getUrl() }}"
                                             alt="project-slider-image"/>
                                    </div>
                                @endforeach


                            </div><!-- Page slider end -->

                        @endif
                        <div class="gap-40"></div>


                        @includeIf($templatePath.'pages.content_object.relations.relation')

                        <div class="gap-40"></div>
                        <div class="row">
                            <div class="col-md-12">
                                {!! BossmanFilamentApp\Http\Controllers\FormsController::startForm($model) !!}
                                {!! BossmanFilamentApp\Http\Controllers\FormsController::form($model, '7b7f3cf24d4b|name',['class'=>'form-control form-control-name','wrapper'=> 'form-group']) !!}
                                {!! BossmanFilamentApp\Http\Controllers\FormsController::form($model, '00b8e751d33d|company_name',['class'=>'form-control form-control-name','wrapper'=> 'form-group']) !!}
                                {!! BossmanFilamentApp\Http\Controllers\FormsController::form($model, '794c0a0153e1|e_mail',['class'=>'form-control form-control-name','wrapper'=> 'form-group']) !!}
                                {!! BossmanFilamentApp\Http\Controllers\FormsController::form($model, '9e498613312d|message',['class'=>'form-control form-control-message','wrapper'=> 'form-group']) !!}
                                {!! BossmanFilamentApp\Http\Controllers\FormsController::submitBtn($model,['btn btn-primary solid blank','class' => 'btn btn-primary solid blank','id' =>  'submit','wrapper' => 'text-right','value' => 'Order Service']) !!}
                                {!! BossmanFilamentApp\Http\Controllers\FormsController::endForm($model) !!}
                            </div>
                        </div>


                    </div><!-- Content inner end -->
                </div><!-- Content Col end -->


            </div><!-- Main row end -->
        </div><!-- Conatiner end -->


    </section><!-- Main container end -->

@endsection
