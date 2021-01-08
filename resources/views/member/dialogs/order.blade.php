<!--选择订单号-->
<div class="panel-body">
    <form class="form-inline queryForm form-horizontal m-b-sm" role="form">
        <input type="text" class="form-control m-l-sm" name="order_number" placeholder="输入订单号">
        <button type="submit" class="btn btn-success m-l-md">查询</button>
    </form>
    <div data-gctable
         data-url="/member/order/3"
         data-label="订单号,客户,报关金额,下单日期,提单号"
         data-query=".queryForm"
         data-operate=""
         data-operateFilter=""
         data-field="number,customer.name,price_clause,created_at,tdnumber"
         data-colWidth="70,70,70,70,70"
         data-minWidth="800px"
         data-save="ordList"
    ></div>
</div>