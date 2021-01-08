<!--境外贸易商管理 :详情，修改，新增-->
<div class="traderdetail">
    <form class="form-horizontal cusName" style="overflow: hidden;">
        <input type="hidden" name="status" value="">
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="traderName" class="col-sm-4 control-label necessary_front">贸易商名称</label>
                <div class="col-sm-8">
                    <input type="text" id="traderName" class="form-control" name="name"
                           value="{{ isset($trader) ? $trader->name : ''}}" data-parsley-required data-parsley-trigger="change" {{ $disabled }} />
                </div>
            </div>
        </div>
         @include('member.common.dataselect', ['label'=>'国家', 'var'=>'country_id', 'obj'=> isset($trader) ? $trader : '', 'col'=>'4'])
        <!-- <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="gjName" class="col-sm-4 control-label necessary_front">国家</label>
                <div class="col-sm-8">
                    <input type="text" id="gjName" name="country" class="form-control" data-parsley-required
                           data-parsley-trigger="change" value="{{ isset($trader) ? $trader->country : ''}}" {{ $disabled }} >
                </div>
            </div>
        </div> -->
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="email" class="col-sm-4 control-label necessary_front">邮箱</label>
                <div class="col-sm-8">
                    <input type="text" id="email" name="email" class="form-control" placeholder=""  data-parsley-required data-parsley-trigger="change" data-parsley-isEmail data-parsley-isEmail-message="请输入正确的email" value="{{ isset($trader) ? $trader->email : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="phoneNumber" class="col-sm-4 necessary_front control-label">手机</label>
                <div class="col-sm-8">
                    <input type="text" id="phoneNumber" name="cellphone" class="form-control"
                           value="{{ isset($trader) ? $trader->cellphone : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="gjurl" class="col-sm-4 control-label">网址</label>
                <div class="col-sm-8">
                    <input type="text" id="gjurl" name="url" class="form-control"
                           value="{{ isset($trader) ? $trader->url : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-12 m-t-xs">
            <div class="form-group">
                <label for="address" class="col-sm-1 necessary_front control-label">地址</label>
                <div class="col-sm-11">
                    <textarea id="address" name="address"  data-parsley-required
                           data-parsley-trigger="change" class="form-control"
                           value="" {{ $disabled }}>{{ isset($trader) ? $trader->address : ''}}</textarea>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

    </form>
</div>
<script>
$(function(){
    $('#country_id').select2()
})
</script>

