<section class="panel fildetail ">
    <form class="form-horizontal"  style="overflow: hidden;">
        {{--1.报关方式--}}
        <div class="m-t-xs m-l-sm" style="line-height: 19px;">
            <b class="badge bg-danger">1</b>
            <label class="h5 text-danger m-l-xs">报关方式</label>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="cusName" class="col-sm-4 control-label necessary_front" >客户</label>
                <div class="col-sm-8">
                    <input type="text" id="cusName" value="{{ isset($order) ? $order->customer->name : (isset($customer) ?  $customer->name : '')}}" class="form-control" placeholder="选择客户" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front" >经营单位</label>
                <div class="col-sm-8">
                    <input type="text"  value="{{ isset($order->company)? $order->company->name:'' }}" class="form-control" readonly/>
                </div>
            </div>
        </div>
        @include('admin.common.dataselect', ['label'=>'报关口岸', 'var'=>'clearance_port', 'obj'=> isset($order) ? $order : ''])
        @include('admin.common.dataselect', ['label'=>'起运港', 'var'=>'shipment_port', 'obj'=> isset($order) ? $order : ''])
        @include('admin.common.dataselect', ['label'=>'报关方式', 'var'=>'declare_mode', 'obj'=> isset($order) ? $order : ''])
        @include('admin.common.dataselect', ['label'=>'业务类型', 'var'=>'business', 'obj'=> isset($order) ? $order : ''])
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="cusName" class="col-sm-4 control-label necessary_front" >合同号</label>
                <div class="col-sm-8">
                    <input type="text"  value="{{ isset($order) ? $order->ordnumber : '' }}" class="form-control"   readonly/>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

        
        <!-- 报关单号 -->
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="customs_number" class="col-sm-4  necessary_front control-label">报关单号</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->customs_number : ''}}" id="customs_number" name="customs_number" class="form-control" data-parsley-required="" data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="export_date" class="col-sm-4  necessary_front control-label">出口日期</label>
                <div class="col-sm-8">
                    <input type="text" onclick="laydate()" value="{{ isset($order) ? $order->export_date : ''}}" id="export_date" name="export_date" class="form-control" data-parsley-required="" data-parsley-trigger="change" readonly/>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(function () {
          if($('#customs_number').val() !==""){
            $('.layui-layer-btn0').css('display','none')
          }
        })
        var customs_number = $("#customs_number").val();

        if( '' != customs_number ){
            $('#customs_number').attr("disabled",true);
            $('#export_date').attr("disabled",true);
        }

    </script>
</section>

