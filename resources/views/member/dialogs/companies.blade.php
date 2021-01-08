<!--选择-->
<div class="panel-body company-tbody">
    <form class="form-inline queryForm form-horizontal m-b-sm" role="form">
        <label for="searchCusName">公司名称</label>
        <input type="text" class="form-control m-l-sm" name="keyword" id="searchCusName" placeholder="需要查找的公司名称">
        <button type="submit" class="btn btn-success m-l-md">查询</button>
    </form>
    <div data-gctable
         data-url="/member/company"
         data-label="id,公司名称"
         data-query=".queryForm"
         data-operate=""
         data-operateFilter=""
         data-field="id,name"
         data-colWidth="50,100"
         data-minWidth="700px"
    ></div>
</div>