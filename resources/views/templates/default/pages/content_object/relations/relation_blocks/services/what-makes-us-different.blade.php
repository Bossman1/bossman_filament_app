<div class="row">
    <div class="col-md-6">
        <h3 class="column-title-small">{{ $model->relation($relation->slug,'d37f7bdaec33') }}</h3>
        {!! $model->relation($relation->slug,'7ea17fca2a29') !!}
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
