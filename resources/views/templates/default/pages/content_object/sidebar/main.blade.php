<div class="sidebar sidebar-left">
    @foreach($sidebar->widgets as $widget)
        {!! \BossmanFilamentApp\Http\Controllers\WidgetsController::getWidget($widget, $model) !!}
    @endforeach
</div><!-- Sidebar end -->
