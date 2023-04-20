@if($model->relation($relation->slug,'657f3f2f78a1'))
    <div class="gap-40"></div>

    <div class="call-to-action classic">
        <div class="row align-items-center">
            <div class="col-md-8 text-center text-md-left">
                <div class="call-to-action-text">
                    <h3 class="action-title">{{ $model->relation($relation->slug,'657f3f2f78a1') }}</h3>
                </div>
            </div><!-- Col end -->
            <div class="col-md-4 text-center text-md-right mt-3 mt-md-0">
                <div class="call-to-action-btn">
                    <a class="btn btn-primary"
                       href="{{ $model->relation($relation->slug,'396d54b03691') }}">Get a Quote</a>
                </div>
            </div><!-- col end -->
        </div><!-- row end -->
    </div><!-- Action end -->
@endif
