<!--选择产品-->
<div class="panel-body">
    <form class="form-inline queryForm form-horizontal m-b-sm" role="form">
        <label for="searchProName">产品名称或者申请人</label>
        <input type="text" name="keyword" class="form-control m-l-sm" id="searchProName" placeholder="输入产品名称或申请人查询">
        <button type="submit" class="btn btn-success m-l-md">查询</button>
    </form>
    <div data-gctable
         data-url="/admin/product/3"
         data-label="产品图片,产品名称,申请人,HSCode,型号,退税率,货号"
         data-query=".queryForm"
         data-check="true"
         data-operate=""
         data-operateFilter=""
         data-field="picture_str,name,created_user_name,hscode,standard,tax_refund_rate,number"
         data-colWidth="70,120,60,100,80,60,80"
         data-minWidth="700px"
         data-save="proList"
    ></div>
</div>