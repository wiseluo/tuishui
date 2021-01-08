<!--品牌弹窗 :详情，修改，新增-->
<div class="personaldetail">
    <form class="form-horizontal personalName" style="overflow: hidden;">
        <input type="hidden" name="status">
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="personalName" class="col-sm-4 control-label necessary_front">开票人名称</label>
                <div class="col-sm-8">
                    <input type="text" id="personalName" name="drawer_name" class="form-control" data-parsley-required
                           data-parsley-trigger="change" value="{{ isset($personal) ? $personal->drawer_name : ''}}" {{ $disabled }} placeholder="输入开票人名称"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="legal_person" class="col-sm-4 control-label">法人</label>
                <div class="col-sm-8">
                    <input type="text" id="legal_person" name="legal_person" class="form-control" value="{{ isset($personal) ? $personal->legal_person : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="legal_person" class="col-sm-4 control-label">账号</label>
                <div class="col-sm-8">
                    <input type="text" id="legal_person" name="legal_person" class="form-control" value="{{ isset($personal->user->username) ? $personal->user->username : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="phone" class="col-sm-4 control-label">联系电话</label>
                <div class="col-sm-8">
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ isset($personal) ? $personal->phone : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="tax_id" class="col-sm-4 control-label">纳税人识别号</label>
                <div class="col-sm-8">
                    <input type="text" id="tax_id" name="tax_id" class="form-control" value="{{ isset($personal) ? $personal->tax_id : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="tax_at" class="col-sm-4 control-label">一般纳税人认定时间</label>
                <div class="col-sm-8">
                    <input type="text" id="tax_at" name="tax_at" class="form-control" value="{{ isset($personal) ? $personal->tax_at : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="original_addr" class="col-sm-4 control-label">境内货源地</label>
                <div class="col-sm-8">
                    <input type="text" id="original_addr" name="original_addr" class="form-control" value="{{ isset($personal) ? $personal->original_addr : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="authPic" class="col-sm-4 control-label">销项发票</label>
                <div class="col-sm-5">
                    <input type="button" id="authPic" class="text-center form-control btn-s btn-primary" value="销项发票" data-upload="image" data-input="[name=pic_invoice]"/>
                    <input type="hidden" name="pic_invoice" value="{{ isset($personal) ? $personal->pic_invoice : ''}}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="authPic" class="col-sm-4 control-label">一般纳税人认定书</label>
                <div class="col-sm-5">
                    <input type="button" id="authPic" class="text-center form-control btn-s btn-primary" value="一般纳税人认定书" data-upload="image" data-input="[name=pic_verification]"/>
                    <input type="hidden" name="pic_verification" value="{{ isset($personal) ? $personal->pic_verification : ''}}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="authPic" class="col-sm-4 control-label">营业执照</label>
                <div class="col-sm-5">
                    <input type="button" id="authPic" class="text-center form-control btn-s btn-primary" value="营业执照" data-upload="image" data-input="[name=pic_register]"/>
                    <input type="hidden" name="pic_register" value="{{ isset($personal) ? $personal->pic_register : ''}}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="authPic" class="col-sm-4 control-label">外贸综合服务协议书</label>
                <div class="col-sm-5">
                    <input type="button" id="authPic" class="text-center form-control btn-s btn-primary" value="外综协议" data-upload="image" data-input="[name=service_agreement_pic]"/>
                    <input type="hidden" name="service_agreement_pic" value="{{ isset($personal) ? $personal->service_agreement_pic : ''}}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="authPic" class="col-sm-4 control-label">垫付退税协议</label>
                <div class="col-sm-5">
                    <input type="button" id="authPic" class="text-center form-control btn-s btn-primary" value="垫付退税协议" data-upload="image" data-input="[name=tax_refund_agreement_pic]"/>
                    <input type="hidden" name="tax_refund_agreement_pic" value="{{ isset($personal) ? $personal->tax_refund_agreement_pic : ''}}">
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        @include('member.common.approve', ['obj'=>isset($personal) ? $personal : ''])
    </form>
</div>
