@if(isset($global_menu))
    @foreach($global_menu as $menu)
        @if($menu->menuChildren->isEmpty())
            <li class="nav-item"><a class="nav-link" href="{{ route('content.view',$menu->slug) }}">{{ $menu->name }}</a></li>
        @else
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown"  href="{{ route('content.view',$menu->slug) }}">{{ $menu->name }} <i class="fa fa-angle-down"></i></a>
                @include($templatePath.'includes.partials.menu_children',['menu'=> $menu, 'children' => $menu->menuChildren])
            </li>
        @endif
    @endforeach
@endif
