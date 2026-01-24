@foreach ($menu as $row)
    @if ($row['type'] == 'link')
        @if($row['permission'])
            <script>
                $('#{{$id}}').css('display','block');
            </script>
            <li class="nav-item {{ active_class($row['active_link']) }}">
                <a href="{{ $row['route'] }}" class="nav-link loader {{ active_class($row['active_link']) }}">
                    {!! $row['icon'] !!}
                    <span class="link-title">{{ $row['name'] }}</span>
                </a>
            </li>
        @endif
    @elseif($row['type'] == 'dropdown')
        @if($row['permission'])
            <script>
                $('#{{$id}}').css('display','block');
            </script>
            <li class="nav-item {{ active_class($row['active_link']) }}">
                <a class="nav-link" data-toggle="collapse" href="#{{$row['id']}}" role="button"
                    aria-expanded="{{ is_active_route($row['active_link']) }}" aria-controls="{{$row['id']}}">
                    {!! $row['icon'] !!}
                    <span class="link-title">{{$row['name']}}</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class($row['active_link']) }}" id="{{$row['id']}}">
                    <ul class="nav sub-menu">
                        @foreach ($row['list'] as $child_row)
                            @if($child_row['permission'])
                                <li class="nav-item">
                                    <a href="{{ $child_row['route'] }}"
                                        class="nav-link loader {{ active_class($child_row['active_link']) }}">{{$child_row['name']}}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </li>
        @endif

    @elseif($row['type'] == 'label')
        <li class="nav-item nav-category">{{$row['label']}}</li>
    @endif
@endforeach
