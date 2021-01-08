<!--选择-->
<div class="panel-body">
    <form class="form-inline queryForm form-horizontal m-b-sm" role="form">
        <label for="searchCusName">境内货源地名称</label>
        <input type="text" class="form-control m-l-sm" name="keyword" id="searchCusName" placeholder="需要查找的境内货源地名称">
        <button type="submit" class="btn btn-success m-l-md">查询</button>
    </form>
    <div data-gctable
         data-url="/member/districtlist"
         data-label="编号,境内货源地名称,类型"
         data-query=".queryForm"
         data-operate=""
         data-operateFilter=""
         data-field="district_code,district_name,district_type"
         data-colWidth="100,100,100,100"
         data-minWidth="700px"
    ></div>
</div>