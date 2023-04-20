<ul class="dropdown-menu" role="menu">
    @foreach($children  as $child)
        @if($child->menuChildren->isNotEmpty())
            <li class="dropdown-submenu">
                <a href="{{ route('content.view',$child->slug) }}"><span class="code-effect">{{ $child->name }}</span></a>
            @include($templatePath.'includes.partials.menu_children',['menu'=> $child, 'children' => $child->menuChildren])
            </li>
        @else
            <li>
                <a href="{{ route('content.view',$child->slug) }}"><span class="code-effect">{{ $child->name }}</span></a>
            </li>
        @endif
    @endforeach
</ul>
