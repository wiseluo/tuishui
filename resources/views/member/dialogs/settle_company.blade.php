<!--选择产品-->
<div class="panel-body title_cll">
    <div data-gctable
         data-url="{{ URL::to('/member/finance/settlement/drawer', [request()->route( 'id' )]) }}"
         data-label="开票工厂"
         data-query=".queryForm"
         data-operate=""
         data-operateFilter=""
         data-field="company"
         data-colWidth="100,0"
         data-minWidth="600px"
         data-save="comList"
    >
    </div>
</div>
