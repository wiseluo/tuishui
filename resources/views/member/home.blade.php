@extends('member.layouts.app')

@section('content')
<section class="vbox">
  <section class="scrollable padder">
    <section class="panel panel-default">
      <div class="panel-body">
        @if($confirm == true)
          welcome to sx tuishui system!
        @else
          当前账号信息不足，请联系管理员完善！
        @endif
      </div>
    </section>
  </section>
</section>
@endsection
