
<section id="ts-team" class="ts-team">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-12">
                <h2 class="section-title">Quality Service</h2>
                <h3 class="section-sub-title">Professional Team</h3>
            </div>
        </div><!--/ Title row end -->

        <div class="row">
            <div class="col-lg-12">
                <div id="team-slide" class="team-slide">

                    @foreach($model->relation($relation->slug) as $relationChildren)
                        <div class="item">
                            <div class="ts-team-wrapper">
                                <div class="team-img-wrapper">
                                    <img loading="lazy" class="w-100" src="{{ $relationChildren->getFirstMediaUrl('gallery') }}" alt="team-img">
                                </div>
                                <div class="ts-team-content">
                                    <h3 class="ts-name">{{ $relationChildren->field('d37f7bdaec33') }}</h3>
                                    <p class="ts-designation">{{ $relationChildren->field('b914590ffc1a') }}</p>
                                    <p class="ts-description">{!! $relationChildren->field('7ea17fca2a29') !!}</p>
                                    <div class="team-social-icons">
                                        <a target="_blank" href="{{ $relationChildren->field('18f4ced56c7e') }}">{!! $relationChildren->field('fe67fe73a2e3') !!}</a>
                                        <a target="_blank" href="{{ $relationChildren->field('288896b72d3b') }}">{!! $relationChildren->field('cf3294fbdfc9') !!}</a>
                                        <a target="_blank" href="{{ $relationChildren->field('d068e55c473f') }}">{!! $relationChildren->field('22a65ce52a9f') !!}</a>
                                        <a target="_blank" href="{{ $relationChildren->field('7ea17fca2a29') }}">{!! $relationChildren->field('c758fd963cfd') !!}</a>
                                    </div><!--/ social-icons-->
                                </div>
                            </div><!--/ Team wrapper end -->
                        </div><!-- Team 1 end -->
                    @endforeach



                </div><!-- Team slide end -->
            </div>
        </div><!--/ Content row end -->
    </div><!--/ Container end -->
</section><!--/ Team end -->
