<!--公司管理 :详情，修改，新增-->
<div class="companydetail m-t">
    <form class="form-horizontal cusName" style="overflow: hidden;">
        <input type="hidden" name="status" value="" class="status">
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="name" class="col-sm-4 control-label necessary_front">公司名称</label>
                <div class="col-sm-8">
                    <input type="text" id="name" class="form-control" name="name" value="{{ isset($company) ? $company->name : ''}}" data-parsley-required data-parsley-trigger="change" {{ $disabled }} />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="prefix" class="col-sm-4 control-label necessary_front">订单编号前缀</label>
                <div class="col-sm-8">
                    <input type="text" id="prefix" class="form-control" name="prefix" value="{{ isset($company) ? $company->prefix : ''}}" data-parsley-required data-parsley-trigger="change" {{ $disabled }} />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="domain" class="col-sm-4 control-label necessary_front">域名</label>
                <div class="col-sm-8">
                    <input type="text" id="domain" class="form-control" name="domain" value="{{ isset($company) ? $company->domain : ''}}" data-parsley-required data-parsley-trigger="change" {{ $disabled }} />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="billingName" class="col-sm-4 control-label necessary_front">开票名称</label>
                <div class="col-sm-8">
                    <input type="text" id="billingName" class="form-control" name="invoice_name" value="{{ isset($company) ? $company->invoice_name : ''}}" data-parsley-required data-parsley-trigger="change" {{ $disabled }} />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="billingName" class="col-sm-4 control-label necessary_front">开票英文名称</label>
                <div class="col-sm-8">
                    <input type="text" id="billingName" class="form-control" name="invoice_en_name" value="{{ isset($company) ? $company->invoice_en_name : ''}}" data-parsley-required data-parsley-trigger="change" {{ $disabled }} />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="tax_id" class="col-sm-4 control-label necessary_front">纳税人识别号</label>
                <div class="col-sm-8">
                    <input type="text" id="tax_id" class="form-control" name="tax_id" value="{{ isset($company) ? $company->tax_id : ''}}" data-parsley-required data-parsley-trigger="change" {{ $disabled }} />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="customs_code" class="col-sm-4 control-label necessary_front">海关编号</label>
                <div class="col-sm-8">
                    <input type="text" id="customs_code" class="form-control" name="customs_code" value="{{ isset($company) ? $company->customs_code : ''}}" data-parsley-required data-parsley-trigger="change" {{ $disabled }} />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="corporate_repre" class="col-sm-4 control-label">法人代表</label>
                <div class="col-sm-8">
                    <input type="text" id="corporate_repre" name="corporate_repre" class="form-control" data-parsley-trigger="change" value="{{ isset($company) ? $company->corporate_repre : ''}}" {{ $disabled }} >
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="address" class="col-sm-4 necessary_front control-label">地址</label>
                <div class="col-sm-8">
                    <input type="text" id="address" name="address"  data-parsley-required data-parsley-trigger="change" class="form-control" value="{{ isset($company) ? $company->address : ''}}" {{ $disabled }}>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="telephone" class="col-sm-4 control-label necessary_front">电话</label>
                <div class="col-sm-8">
                    <input type="text" id="telephone" name="telephone" class="form-control" data-parsley-required data-parsley-trigger="change" value="{{ isset($company) ? $company->telephone : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="bankname" class="col-sm-4 necessary_front control-label">开户行</label>
                <div class="col-sm-8">
                    <input type="text" id="bankname" name="bankname" class="form-control" data-parsley-required data-parsley-trigger="change" value="{{ isset($company) ? $company->bankname : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="bankaccount" class="col-sm-4 necessary_front control-label">账号</label>
                <div class="col-sm-8">
                    <input type="text" id="bankaccount" name="bankaccount" class="form-control" data-parsley-required data-parsley-trigger="change" value="{{ isset($company) ? $company->bankaccount : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="invoice_receipt_addr" class="col-sm-4 necessary_front control-label">发票收件地址</label>
                <div class="col-sm-8">
                    <input type="text" id="invoice_receipt_addr" name="invoice_receipt_addr" class="form-control" data-parsley-required data-parsley-trigger="change" value="{{ isset($company) ? $company->invoice_receipt_addr : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="invoice_recipient" class="col-sm-4 necessary_front control-label">发票收件人</label>
                <div class="col-sm-8">
                    <input type="text" id="invoice_recipient" name="invoice_recipient" class="form-control" data-parsley-required data-parsley-trigger="change" value="{{ isset($company) ? $company->invoice_recipient : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="recipient_call" class="col-sm-4 necessary_front control-label">收件人电话</label>
                <div class="col-sm-8">
                    <input type="text" id="recipient_call" name="recipient_call" class="form-control" data-parsley-required data-parsley-trigger="change" value="{{ isset($company) ? $company->recipient_call : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="exemption_nature" class="col-sm-4 necessary_front control-label">征免性质</label>
                <div class="col-sm-8">
                    <input type="text" id="exemption_nature" name="exemption_nature" class="form-control" data-parsley-required data-parsley-trigger="change" value="{{ isset($company) ? $company->exemption_nature : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="exemption_code" class="col-sm-4 necessary_front control-label">征免性质编号</label>
                <div class="col-sm-8">
                    <input type="text" id="exemption_code" name="exemption_code" class="form-control" data-parsley-required data-parsley-trigger="change" value="{{ isset($company) ? $company->exemption_code : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

    </form>
</div>
