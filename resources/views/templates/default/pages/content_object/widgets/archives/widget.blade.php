@if(isset($widgetArchives) && $widgetArchives)

    <div class="widget">
        <h3 class="widget-title">{{ __('Archives') }} </h3>
        <ul class="arrow nav nav-tabs">
            @foreach($widgetArchives as $widgetArchive)
                <li><a href="{{ route('content.archive',[$widgetArchive->view,$widgetArchive->parent_key,$widgetArchive->to]) }}">{{ $widgetArchive->label }}</a></li>
            @endforeach


        </ul>
    </div><!-- Archives end -->
@endif
