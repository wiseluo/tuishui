@if('approve' === request()->type || ('0' !== request()->id && ($obj->status >= 3)))
    {{--以下三项审批通过&拒绝的查看页面以及审核页面可见--}}
    <div class="line line-dashed"></div>
    <div class="col-sm-4 m-t-sm">
        <div class="form-group">
            <label class="col-sm-4 control-label">审核结果</label>
            <div class="col-sm-8 audit-select">
                {{ Form::select('', ['3'=>'审核通过', '4'=>'审核拒绝'], $obj->status ? $obj->status : '', [
                    'class' => 'form-control result',
                    'data-parsley-required',
                    'data-parsley-trigger' => 'change',
                    $obj->status == 2 ? '' : 'disabled',
                ]) }}
            </div>
        </div>
    </div>
    <div class="col-sm-4 m-t-sm">
        <div class="form-group">
            <label class="col-sm-4 control-label">审核日期</label>
            <div class="col-sm-8">
                <input type="text" name="approved_at" {{ $obj->status == 2 ? '' : 'disabled' }} value="{{ $obj->status == 2 ? \Carbon\Carbon::now()->format('Y-m-d') : $obj->approved_at }}" class="form-control" readonly/>
            </div>
        </div>
    </div>
    <div class="col-sm-12 m-t-sm">
        <div class="form-group">
            <label class="col-sm-1 control-label" style="padding:7px 0;">审核意见</label>
            <div class="col-sm-11">
                <textarea name="opinion" class="form-control opinion audit-opinion" {{ $obj->status == 2 ? '' : 'disabled' }} rows="3">{{ $obj->opinion ? $obj->opinion : '' }}</textarea>
            </div>
        </div>
    </div>
@endif