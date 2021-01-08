<!--产品修改日志-->
<div class="panel-body">
    <form class="form-inline queryForm form-horizontal m-b-sm" role="form">
        <label for="searchProName">产品名称</label>
        <input type="text" name="keyword" class="form-control m-l-sm" id="searchProName" placeholder="产品名称">
        <button type="submit" class="btn btn-success m-l-md">查询</button>
    </form>
    <div data-gctable
         data-url="/member/productlog"
         data-label="产品名称,ip,内容"
         data-query=".queryForm"
         data-check="true"
         data-operate=""
         data-operateFilter=""
         data-field="product.name,ip,content"
         data-colWidth="70,50,160"
         data-minWidth="700px"
         data-save="proList"
    ></div>
</div>