<div class="row text-center">
    <div class="col-12">
        <h2 class="section-title">{{ $relation->field('334b0636b035') }}</h2>
    </div>
</div>
<!--/ Title row end -->

<div class="row">
    @foreach($model->relation($relation->slug) as $relationChildren)
        <div class="col-md-4">
            <div class="ts-service-box-bg text-center h-100">
                      <span class="ts-service-icon icon-round">
                       {!! $relationChildren->field('b914590ffc1a') !!}
                      </span>
                <div class="ts-service-box-content">
                    <h4>{!! $relationChildren->field('d37f7bdaec33') !!}</h4>
                    <p>{!! $relationChildren->field('7ea17fca2a29') !!}</p>
                </div>
            </div>
        </div><!-- Col 1 end -->
    @endforeach
</div><!-- 1st row end -->



