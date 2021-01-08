<!--选择-->
<div class="panel-body">
    <form class="form-inline queryForm form-horizontal m-b-sm" role="form">
        <label for="searchCusName">贸易商名称</label>
        <input type="text" class="form-control m-l-sm" name="keyword" id="searchCusName" placeholder="需要查找的贸易商名称">
        <button type="submit" class="btn btn-success m-l-md">查询</button>
    </form>
    <div data-gctable
         data-url="/member/trader"
         data-label="贸易商名称,国家,地址,联系电话"
         data-query=".queryForm"
         data-operate=""
         data-operateFilter=""
         data-field="name,country_str_en,address,cellphone"
         data-colWidth="100,100,100,100"
         data-minWidth="700px"
    ></div>
</div>