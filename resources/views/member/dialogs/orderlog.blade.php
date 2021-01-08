<!--订单修改日志-->
<div class="panel-body">
    <form class="form-inline queryForm form-horizontal m-b-sm" role="form">
        <label for="searchOrdName">订单</label>
        <input type="text" name="keyword" class="form-control m-l-sm" id="searchOrdName" placeholder="订单号">
        <button type="submit" class="btn btn-success m-l-md">查询</button>
    </form>
    <div data-gctable
         data-url="/member/orderlog"
         data-label="订单号,ip,订单修改,产品修改,修改时间"
         data-query=".queryForm"
         data-check="true"
         data-operate=""
         data-operateFilter=""
         data-field="order.ordnumber,ip,ord_log,pro_log,created_at"
         data-colWidth="70,55,120,120,40"
         data-minWidth="700px"
         data-save="proList"
    ></div>
</div>