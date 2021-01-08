<!--选择产品-->
<div class="panel-body">
    <form class="form-inline queryForm form-horizontal m-b-sm" role="form">
        <input type="text" class="form-control m-l-sm" name="contractNumber" placeholder="输入订单号/发票号">
        <button type="submit" class="btn btn-success m-l-md">查询</button>
    </form>
    <div data-gctable
         data-url="/admin/invoice/3"
         data-label="订单号,发票号,开票时间,开票金额,收票时间"
         data-query=".queryForm"
         data-check="true"
         data-operate=""
         data-operateFilter=""
         data-field="order.ordnumber,number,billed_at,invoice_amount,received_at"
         data-colWidth="70,100,100,100,80,80"
         data-minWidth="700px"
         data-save="invList"
    ></div>
</div>