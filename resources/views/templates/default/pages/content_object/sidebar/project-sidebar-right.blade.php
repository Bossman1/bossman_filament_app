<div class="sidebar sidebar-right">

    @foreach($sidebar->widgets as $widget)
        {!! \BossmanFilamentApp\App\Http\Controllers\WidgetsController::getWidget($widget, $model) !!}
    @endforeach
</div><!-- Sidebar end -->
