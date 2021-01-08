<style>
    .parsley-errors-list{
        margin-top:3px;
    }
</style>
<!--权限修改，新增-->
<div class="panel-body busdetail">
    <form class="form-horizontal" style="overflow: hidden;">
        <div class="form-group">
            <label for="" class="col-sm-2 control-label necessary_front">经营单位</label>
            <div class="col-sm-9">
                {{ Form::select('is_pay_special', $salesUnit, isset($bus) ? $bus->sales_unit : '', [
                        'name' => 'sales_unit',
                        'class' => 'form-control',
                        'data-parsley-required',
                        'data-parsley-trigger' => 'change',
                 ]) }}
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label necessary_front">业务类型</label>
            <div class="col-sm-9">
                {{ Form::select('is_pay_special', $business, isset($bus) ? $bus->business : '', [
                            'name' => 'business',
                            'class' => 'form-control',
                            'data-parsley-required',
                            'data-parsley-trigger' => 'change',
                ]) }}
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label necessary_front">合同类型</label>
            <div class="col-sm-9">
                <input type="text" value="{{ isset($bus) ? $bus->contract : '' }}" name="contract" class="form-control" data-parsley-required data-parsley-trigger="change"/>
            </div>
        </div>
    </form>
</div>