<!--选择-->
<div class="panel-body account-tbody">
    <form class="form-inline queryForm form-horizontal m-b-sm" role="form">
        <label for="searchCusName">账号名称</label>
        <input type="text" class="form-control m-l-sm" name="keyword" id="searchCusName" placeholder="需要查找的账号名称">
        <button type="submit" class="btn btn-success m-l-md">查询</button>
    </form>
    <div data-gctable
         data-url="/member/system/account"
         data-label="id,账号名称,用户昵称"
         data-query=".queryForm"
         data-operate=""
         data-operateFilter=""
         data-field="id,username,name"
         data-colWidth="50,70,100"
         data-minWidth="700px"
    ></div>
</div>