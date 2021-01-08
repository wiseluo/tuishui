<ul class="nav nav-tabs pull-left">
    @foreach ($statuses as $key=>$status)
        <li><a href="/{{ $link }}/{{ $key }}" >{{ $status }}</a></li>
    @endforeach
</ul>