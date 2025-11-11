<div>
    @foreach ($items as $item)
        @if ($item->type == 'divider')
            <li class="mt-3">
                <div class="menu-title text-uppercase text-primary fw-bold">{{$item->divider_title}}</div>
                <hr>
            </li>
            @else
            @if ($item->childs->isEmpty())
                    {{-- <ul> --}}

                        <li>
                            <a href="{{$item->url}}">
                                <div class="parent-icon">
                                    <i class="{{$item->icon_class}}"></i>
                                </div>
                                <div class="menu-title">{{$item->title}}</div>
                            </a>
                        </li>
                    {{-- </ul> --}}


            @else

                <li>
                    {{-- @can('{{$item->url}}.permission') --}}
                        <a href="{{$item->url}}" class="has-arrow">
                            <div class="parent-icon ">
                                <i class="{{$item->icon_class}}"></i>
                            </div>
                            <div class="menu-title">{{$item->title}}</div>
                        </a>

                    {{-- @endcan --}}
                    <ul>
                        @foreach ($item->childs as $child)
                            @can("{$child->url}.permission")
                                <li>
                                    <a href="/{{$child->url}}">
                                        <div class="parent-icon">
                                            <i class="{{$child->icon_class}}"></i>
                                        </div>
                                        <div class="menu-title">{{$child->title}}</div>
                                    </a>
                                </li>
                            @endcan
                        @endforeach
                    </ul>
                </li>
            @endif
        @endif
    @endforeach
</div>
