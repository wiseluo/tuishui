<!--境外收货人-->
<div class="panel-body">
    <form class="form-inline queryForm form-horizontal m-b-sm" role="form">
        <label for="searchProName">境外收货人</label>
        <input type="text" name="keyword" class="form-control m-l-sm" id="searchProName" placeholder="境外收货人">
        <button type="submit" class="btn btn-success m-l-md">查询</button>
    </form>
    <div data-gctable
         data-url="/member/receive"
         data-label="名称,国家,详细地址,AEO编码,传真,网址,联系电话"
         data-query=".queryForm"
         data-check="true"
         data-operate=""
         data-operateFilter=""
         data-field="product.name,ip,content"
         data-colWidth="70,50,70,40,40,40,40"
         data-minWidth="700px"
         data-save="proList"
    ></div>
</div>