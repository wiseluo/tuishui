<!--选择产品-->
<div class="panel-body">
    {{--<form class="form-inline queryForm form-horizontal m-b-sm" role="form">
        <input type="text" class="form-control m-l-sm" placeholder="输入产品名称">
        <button type="submit" class="btn btn-success m-l-md">查询</button>
    </form>--}}
    <div data-gctable
         data-url="{{ URL::to('/admin/drawer/3') }}"
         data-label="公司"
         data-query=".queryForm"
         data-operate=""
         data-operateFilter=""
         data-field="company"
         data-colWidth="100,0"
         data-minWidth="600px"
         data-save="firList"
    ></div>
</div>