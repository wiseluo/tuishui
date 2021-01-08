<!--选择产品-->
<div class="panel-body">
    <div data-gctable
         data-url="{{ URL::to('/admin/order/deposit', [request()->route('id')]) }}"
         data-label="付款单号,金额,款项内容,付款日期"
         data-query=".queryForm"
         data-operate=""
         data-operateFilter=""
         data-field="pay_number,money,content,pay_at"
         data-colWidth="70,70,70,70"
         data-minWidth="600px"
         data-save="ordList"
    ></div>

</div>