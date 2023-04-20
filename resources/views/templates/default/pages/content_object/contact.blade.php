@extends($templatePath.'layouts.main')
@section('content')


    <section id="main-container" class="main-container">
        <div class="container">


            @includeIf($templatePath.'pages.content_object.relations.relation')



            <div class="gap-60"></div>

                {!! $model->field('b1fccafef4e3') !!}

            <div class="gap-40"></div>

            <div class="row">
                <div class="col-md-12">
                    <h3 class="column-title">{{ $model->field('11f8bc158ef2') }}</h3>
                    {!! BossmanFilamentApp\Http\Controllers\FormsController::startForm($model) !!}

                    <div class="row">
                        <div class="col-md-6">
                            {!! BossmanFilamentApp\Http\Controllers\FormsController::form($model, 'b4760d06af96|name',['class'=>'form-control form-control-name','wrapper'=> 'form-group']) !!}
                        </div>
                        <div class="col-md-6">
                            {!! BossmanFilamentApp\Http\Controllers\FormsController::form($model, '05bea137eb09|subject',['class'=>'form-control form-control-name','wrapper'=> 'form-group']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!! BossmanFilamentApp\Http\Controllers\FormsController::form($model, '417507bb869c|email',['class'=>'form-control form-control-name','wrapper'=> 'form-group']) !!}
                        </div>
                        <div class="col-md-6">
                            {!! BossmanFilamentApp\Http\Controllers\FormsController::form($model, 'be60af2911e5|phone',['class'=>'form-control form-control-name','wrapper'=> 'form-group']) !!}
                        </div>

                    </div>
                    {!! BossmanFilamentApp\Http\Controllers\FormsController::form($model, '55efd3ab9fe5|message',['class'=>'form-control form-control-message','rows' => '10','wrapper'=> 'form-group']) !!}
                    {!! BossmanFilamentApp\Http\Controllers\FormsController::submitBtn($model,['btn btn-primary solid blank','class' => 'btn btn-primary solid blank','id' =>  'submit','wrapper' => 'text-right','value' => 'Submit Contact form']) !!}
                    {!! BossmanFilamentApp\Http\Controllers\FormsController::endForm($model) !!}
                </div>

            </div><!-- Content row -->
        </div><!-- Conatiner end -->
    </section><!-- Main container end -->

@endsection

@push('js')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcABaamniA6OL5YvYSpB3pFMNrXwXnLwU" defer></script>
    <script src="/plugins/google-map/map.js" defer></script>
@endpush
