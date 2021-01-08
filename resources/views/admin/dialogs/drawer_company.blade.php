<!--选择产品-->
<div class="panel-body title_cll">
    <form class="form-inline queryForm form-horizontal m-b-sm" role="form">
        <input type="text" class="form-control m-l-sm" name="keyword" placeholder="输入开票工厂名称">
        <button type="submit" class="btn btn-success m-l-md">查询</button>
    </form>
    <div data-gctable
         data-url="{{ URL::to('/admin/drawer/3') }}"
         data-label="开票工厂"
         data-query=".queryForm"
         data-operate=""
         data-operateFilter=""
         data-field="company"
         data-colWidth="100,0"
         data-minWidth="600px"
         data-save="comList"
    ></div>
</div>
