<!--境外贸易商管理 :详情，修改，新增-->
<div class="billingdetail">
    <form class="form-horizontal cusName" style="overflow: hidden;">
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="dataName" class="col-sm-4 control-label">经营单位名称</label>
                <div class="col-sm-8">
                    <input type="text" id="dataName" class="form-control"
                           value="{{ isset($data) ? $data->name : ''}}" data-parsley-required data-parsley-trigger="change" disabled/>
                    <input type="hidden" name="data_id" value="{{ isset($data) ? $data->id : '' }}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="billingName" class="col-sm-4 control-label necessary_front">开票名称</label>
                <div class="col-sm-8">
                    <input type="text" id="billingName" class="form-control" name="name"
                           value="{{ isset($data) ? (isset($data->billing) ? $data->billing->name : '') : ''}}" data-parsley-required data-parsley-trigger="change" {{ $disabled }} />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="identification_num" class="col-sm-4 control-label necessary_front">纳税人识别号</label>
                <div class="col-sm-8">
                    <input type="text" id="identification_num" name="identification_num" class="form-control" data-parsley-required
                           data-parsley-trigger="change" value="{{ isset($data) ? (isset($data->billing) ? $data->billing->identification_num : '') : ''}}" {{ $disabled }} >
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="corporate_repre" class="col-sm-4 control-label">法人代表</label>
                <div class="col-sm-8">
                    <input type="text" id="corporate_repre" name="corporate_repre" class="form-control" data-parsley-trigger="change" value="{{ isset($data) ? (isset($data->billing) ? $data->billing->corporate_repre : '') : ''}}" {{ $disabled }} >
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="address" class="col-sm-4 necessary_front control-label">地址</label>
                <div class="col-sm-8">
                    <input type="text" id="address" name="address"  data-parsley-required
                           data-parsley-trigger="change" class="form-control"
                           value="{{ isset($data) ? (isset($data->billing) ? $data->billing->address : '') : ''}}" {{ $disabled }}>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="telephone" class="col-sm-4 control-label necessary_front">电话</label>
                <div class="col-sm-8">
                    <input type="text" id="telephone" name="telephone" class="form-control" placeholder=""  data-parsley-required data-parsley-trigger="change" data-parsley-isEmail data-parsley-isEmail-message="请输入正确的email" value="{{ isset($data) ? (isset($data->billing) ? $data->billing->telephone : '') : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="bankname" class="col-sm-4 necessary_front control-label">开户行</label>
                <div class="col-sm-8">
                    <input type="text" id="bankname" name="bankname" class="form-control"
                           value="{{ isset($data) ? (isset($data->billing) ? $data->billing->bankname : '') : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="bankaccount" class="col-sm-4 necessary_front control-label">账号</label>
                <div class="col-sm-8">
                    <input type="text" id="bankaccount" name="bankaccount" class="form-control"
                           value="{{ isset($data) ? (isset($data->billing) ? $data->billing->bankaccount : '') : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="invoice_receipt_addr" class="col-sm-4 necessary_front control-label">发票收件地址</label>
                <div class="col-sm-8">
                    <input type="text" id="invoice_receipt_addr" name="invoice_receipt_addr" class="form-control"
                           value="{{ isset($data) ? (isset($data->billing) ? $data->billing->invoice_receipt_addr : '') : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="invoice_recipient" class="col-sm-4 necessary_front control-label">发票收件人</label>
                <div class="col-sm-8">
                    <input type="text" id="invoice_recipient" name="invoice_recipient" class="form-control"
                           value="{{ isset($data) ? (isset($data->billing) ? $data->billing->invoice_recipient : '') : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="recipient_call" class="col-sm-4 necessary_front control-label">收件人电话</label>
                <div class="col-sm-8">
                    <input type="text" id="recipient_call" name="recipient_call" class="form-control"
                           value="{{ isset($data) ? (isset($data->billing) ? $data->billing->recipient_call : '') : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="customs_code" class="col-sm-4 necessary_front control-label">海关编码</label>
                <div class="col-sm-8">
                    <input type="text" id="customs_code" name="customs_code" class="form-control"
                           value="{{ isset($data) ? (isset($data->billing) ? $data->billing->customs_code : '') : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

    </form>
</div>

