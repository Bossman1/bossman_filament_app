@if($model->relation_blocks)
    <div class="row">
        <div class="col-md-6">
            <h3 class="column-title-small">{{ $model->relation('what-makes-us-different','d37f7bdaec33') }}</h3>
            {!! $model->relation('what-makes-us-different','7ea17fca2a29') !!}
        </div>

        @if($model->relation('you-should-know-block'))
            <div class="col-md-6 mt-5 mt-md-0">
                <h3 class="column-title-small">You Should Know</h3>

                <div class="accordion accordion-group accordion-classic" id="construction-accordion">


                    @foreach($model->relation('you-should-know-block') as $relationChildren)

                        <div class="card">
                            <div class="card-header p-0 bg-transparent" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-block text-left" type="button"
                                            data-toggle="collapse"
                                            data-target="#{{ $relationChildren->slug }}_{{ $relationChildren->id }}"
                                            aria-expanded="@if($loop->index == 0) true @endif"
                                            aria-controls="{{ $relationChildren->slug }}_{{ $relationChildren->id }}">
                                        {{ $relationChildren->field('d37f7bdaec33') }}
                                    </button>
                                </h2>
                            </div>

                            <div id="{{ $relationChildren->slug }}_{{ $relationChildren->id }}"
                                 class="collapse @if($loop->index == 0) show @endif" aria-labelledby="headingOne"
                                 data-parent="#construction-accordion">
                                <div class="card-body">{!! $relationChildren->field('7ea17fca2a29') !!}</div>
                            </div>
                        </div>
                    @endforeach


                </div>
                <!--/ Accordion end -->
                @endif
            </div>
    </div>
    <!--2nd row end -->

    @if($model->relation('interested-with-this-service','657f3f2f78a1'))
        <div class="gap-40"></div>

        <div class="call-to-action classic">
            <div class="row align-items-center">
                <div class="col-md-8 text-center text-md-left">
                    <div class="call-to-action-text">
                        <h3 class="action-title">{{ $model->relation('interested-with-this-service','657f3f2f78a1') }}</h3>
                    </div>
                </div><!-- Col end -->
                <div class="col-md-4 text-center text-md-right mt-3 mt-md-0">
                    <div class="call-to-action-btn">
                        <a class="btn btn-primary"
                           href="{{ $model->relation('interested-with-this-service','396d54b03691') }}">Get a Quote</a>
                    </div>
                </div><!-- col end -->
            </div><!-- row end -->
        </div><!-- Action end -->
    @endif
@endif
