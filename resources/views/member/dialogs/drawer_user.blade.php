<!--选择产品-->
<div class="panel-body">
    {{--<form class="form-inline queryForm form-horizontal m-b-sm" role="form">
        <input type="text" class="form-control m-l-sm" placeholder="输入产品名称">
        <button type="submit" class="btn btn-success m-l-md">查询</button>
    </form>--}}
    <div data-gctable
         data-url="/member/drawer/3"
         data-label="开票人公司名称,纳税人识别号,客户名称"
         data-query=".queryForm"
         data-operate=""
         data-operateFilter=""
         data-field="company,tax_id,customer.name"
         data-colWidth="70,70,50"
         data-minWidth="700px"
    ></div>
</div>