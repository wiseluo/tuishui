<!--选择权限-->
<div class="panel-body">
    <form class="form-inline queryForm form-horizontal m-b-sm" role="form">
        <input type="text" name="keyword" class="form-control m-l-sm" placeholder="输入权限名称">
        <button type="submit" class="btn btn-success m-l-md">查询</button>
    </form>
    <div data-gctable
         data-url="/admin/system/permission"
         data-label="权限名称,描述"
         data-query=".queryForm"
         data-check="true"
         data-operate=""
         data-operateFilter=""
         data-field="name,description"
         data-colWidth="70,100"
         data-minWidth="600px"
         data-save="perList"
    ></div>
</div>