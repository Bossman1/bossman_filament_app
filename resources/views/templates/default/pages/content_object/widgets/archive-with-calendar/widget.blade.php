@if(isset($widgetCalendarArchives) && $widgetCalendarArchives)

    <div class="widget">
        <h3 class="widget-title">{{ __('Archives with calendar') }} </h3>
        <div class="calendar">
            <div class="calendar__picture">
                <h2>{{ $widgetCalendarArchives->currentDay }}, {{ $widgetCalendarArchives->weekDay }}</h2>
                <h3>{{ $widgetCalendarArchives->currentMonth }}</h3>
            </div>
            <div class="calendar__date">
                <div class="calendar__day">M</div>
                <div class="calendar__day">T</div>
                <div class="calendar__day">W</div>
                <div class="calendar__day">T</div>
                <div class="calendar__day">F</div>
                <div class="calendar__day">S</div>
                <div class="calendar__day">S</div>
                @for ($i = 0; $i < $widgetCalendarArchives->dayOfWeek; $i++)
                    <div class="calendar__number"></div>
                @endfor


                @foreach($widgetCalendarArchives->dates as $key =>  $date)

                    @if(isset($date->view))
                        <a href="{{ route('content.archive',[$date->view,$date->parent_key,$date->to]) }}">  @endif
                            <div class="calendar__number @if($date->record) calendar__number--records @endif  @if($date->today) calendar__number--current @endif">{{ $date->label }}</div>

                            @if(isset($date->view))</a>
                    @endif

                @endforeach

            </div>
        </div>
    </div><!-- Archives end -->

@endif

@push('css')
    {!! BossmanFilamentApp\Http\Controllers\WidgetsController::getWidgetStyle($widget) !!}
@endpush

