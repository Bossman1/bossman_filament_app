@if(isset($widgetRelatedObjects) && $widgetRelatedObjects && count($widgetRelatedObjects))
    <div class="widget  recent-posts">
        <h3 class="widget-title">{{ $widget->name }}</h3>
        <ul class="list-unstyled">
            @foreach($widgetRelatedObjects as $widgetRelatedObjects)
                <li class="d-flex align-items-center">
                    @if($widgetRelatedObjects->getFirstMediaUrl('gallery'))
                        <div class="posts-thumb">
                            <a href="{{ route('content.view',$widgetRelatedObjects->slug ) }}"><img loading="lazy" alt="img"
                                                                                             src="{{ $widgetRelatedObjects->getFirstMediaUrl('gallery') }}"></a>
                        </div>
                    @endif
                    <div class="post-info">
                        <h4 class="entry-title">
                            <a href="{{ route('content.view',$widgetRelatedObjects->slug ) }}">{{ $widgetRelatedObjects->getName() }}</a>
                        </h4>
                    </div>
                </li>
            @endforeach
        </ul>
    </div><!-- Widget end -->
@endif
