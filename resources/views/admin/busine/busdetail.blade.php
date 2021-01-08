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
                {{ Form::select('is_pay_special', ['否','是'], '', [
                        'firm_id' => 'firm_id',
                        'class' => 'form-control',
                        'data-parsley-required',
                        'data-parsley-trigger' => 'change',
                 ]) }}
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label necessary_front">业务类型</label>
            <div class="col-sm-9">
                <input type="text" id="" value="" class="form-control" placeholder="选择业务类型" data-select="business" data-target="[name=business_id]" data-outwidth="600px" data-parsley-required data-parsley-trigger="change" readonly/>
                <input type="hidden" name="business_id" value="">
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label necessary_front">合同类型</label>
            <div class="col-sm-9">
                <input type="text" id="" name="contract" class="form-control" data-parsley-required data-parsley-trigger="change"/>
            </div>
        </div>
    </form>
</div>