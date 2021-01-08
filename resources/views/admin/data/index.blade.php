@extends('admin.layouts.app')

@section('content')
    <section class="vbox">
        <section class="scrollable padder">
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="/"><i class="fa fa-home"></i> 数据维护</a></li>
            </ul>
            <section class="row">
                <!-- 侧边导航 -->
                <aside class="col-md-2 sidebarLeft" style="width:11%;">
                    <ul class="nav nav-pills nav-stacked">
                        @forelse($types as $k=>$type)
                            <li role="presentation" class="text-center {{ $k === 0 ? 'active' : ''}}">
                                <a href="#box{{ $k }}" data-toggle="tab" style="padding:5px 10px">{{ $type->name }}</a>
                            </li>
                        @empty
                        @endforelse
                    </ul>
                </aside>
                <!-- 右侧表格 -->
                <section class="col-md-10" style="padding-left:0;">
                    <div class="panel panel-default">
                        <div class="tab-content">
                            @foreach($types as $k=>$type)
                                <div class="tab-pane active" id="box{{ $k }}">
                                    <div data-table="jqgrid" data-url=" {{ url("/admin/data/{$type->id}") }}"
                                         data-colName="编号,显示值,中文名,英文名" data-colModel="key,name,value" data-editrules="required,required,required">
                                        <table></table>
                                        <div></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            </section>
        </section>
    </section>
    <script type="text/javascript">
      $(document).ready(function(){
        init();
        function init()
        {
          $.each($('.tab-pane'), function(k, v){
            if ($(this).attr('id') != 'box0')
              $(this).removeClass('active');
              $(this).attr("")
          });
        }
      });
    </script>
    <script src="{{ URL::asset('/js/item/data.js') }}"></script>
@endsection
