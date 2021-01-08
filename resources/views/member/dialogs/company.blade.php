<!--选择产品-->
<div class="panel-body title_cll">
    <form class="form-inline queryForm form-horizontal m-b-sm" role="form">
        <input type="text" class="form-control m-l-sm" name="keyword" placeholder="输入公司名称">
        <button type="submit" class="btn btn-success m-l-md">查询</button>
    </form>
    <div data-gctable
         data-url="{{ URL::to('/member/company') }}"
         data-label="公司名称"
         data-query=".queryForm"
         data-operate=""
         data-operateFilter=""
         data-field="name"
         data-colWidth="100,0"
         data-minWidth="600px"
         data-save="comList"
    ></div>
</div>
