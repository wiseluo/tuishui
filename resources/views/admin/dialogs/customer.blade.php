<!--选择客户-->
<div class="panel-body">
    <form class="form-inline queryForm form-horizontal m-b-sm" role="form">
        <label for="searchCusName">客户名称</label>
        <input type="text" class="form-control m-l-sm" name="keyword" id="searchCusName" placeholder="需要查找的客户名称">
        <button type="submit" class="btn btn-success m-l-md">查询</button>
    </form>
    <div data-gctable
         data-url="/admin/customer/3"
         data-label="客户名称,客户编号,联系人,联系电话"
         data-query=".queryForm"
         data-operate=""
         data-operateFilter=""
         data-field="name,number,linkman,telephone"
         data-colWidth="70,100,100,100"
         data-minWidth="700px"
    ></div>
</div>