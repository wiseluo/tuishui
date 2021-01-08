<!--选择付款信息-->
<div class="panel-body rem-Ite">
    <form class="form-inline queryForm form-horizontal m-b-sm" role="form">
        <input type="text" name="keyword" class="form-control m-l-sm" placeholder="输入收款单位名称">
        <button type="submit" class="btn btn-success m-l-md">查询</button>
    </form>
    <div data-gctable
         data-url="{{ URL::to('/member/finance/remittee') }}"
         data-label="收款单位类型,收款单位,收款户名,收款账号,开户银行"
         data-query=".queryForm"
         data-operate=""
         data-operateFilter=""
         data-field="remit_type_str,tag,name,number,bank"
         data-colWidth="90,100,100,100,100,70,"
         data-minWidth="600px"
         data-save="comList"
    ></div>

</div>