@extends($templatePath.'layouts.main')
@section('content')

    <section id="main-container" class="main-container">
        <div class="container">

            <div class="row">

                <div class="col-12">
                    <div class="error-page text-center">
                        @if(isset($status))
                        <div class="error-code">
                            <h2><strong>404</strong></h2>
                        </div>
                        @endif
                        @if(isset($message))
                            <div class="error-message">
                                <h3 style="text-transform: initial;">   {!! $message !!}</h3>
                            </div>
                        @endif

                        @if(isset($errors))
                            <ul style="list-style: none">
                                @foreach($errors as $error)
                                    <li><h5>{{ $error }}</h5></li>
                                @endforeach
                            </ul>

                        @endif

                        <div class="error-body">
                            {{--                           Try using the button below to go to main page of the site --}}
                            <br>
                            <a href="{{ route('index') }}" class="btn btn-primary">Back to Home Page</a>
                       </div>
                   </div>
               </div>

           </div><!-- Content row -->
       </div><!-- Conatiner end -->
   </section><!-- Main container end -->

@endsection
