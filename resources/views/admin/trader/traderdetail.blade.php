<!--境外贸易商管理 :详情，修改，新增-->
<div class="traderdetail m-t">
    <form class="form-horizontal cusName" style="overflow: hidden;">
        <input type="hidden" name="status" value="" class="status">
        <input type="hidden" name="id" value="{{ isset($trader) ? $trader->id : ''}}">
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="traderName" class="col-sm-4 control-label necessary_front">贸易商名称</label>
                <div class="col-sm-8">
                    <input type="text" id="traderName" class="form-control" name="name"
                           value="{{ isset($trader) ? $trader->name : ''}}" data-parsley-required data-parsley-trigger="change" {{ $disabled }} />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">客户名称</label>
                <div class="col-sm-8">
                    <input type="text" id="cusName" class="form-control choose_Cus" placeholder="点击选择客户" data-parsley-required data-parsley-trigger="change" value="{{ isset($trader->customer->name) ? $trader->customer->name : ''}}" {{ $disabled }} readonly/>
                    <input type="hidden" value="{{ isset($trader->customer_id) ? $trader->customer_id : ''}}" name="customer_id" id="customer_id">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">国家</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($trader->country->country_na) ? $trader->country->country_na : ''}}" class="form-control country_openCon" data-parsley-required data-parsley-trigger="change" readonly {{ $disabled }} />
                    <input type="hidden" name="country_id" value="{{ isset($trader) ? $trader->country_id : ''}}" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="email" class="col-sm-4 control-label necessary_front">邮箱</label>
                <div class="col-sm-8">
                    <input type="text" id="email" name="email" class="form-control" placeholder=""  data-parsley-required data-parsley-trigger="change" data-parsley-isEmail data-parsley-isEmail-message="请输入正确的email" value="{{ isset($trader) ? $trader->email : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="phoneNumber" class="col-sm-4 necessary_front control-label">手机</label>
                <div class="col-sm-8">
                    <input type="text" id="phoneNumber" name="cellphone" class="form-control"
                           value="{{ isset($trader) ? $trader->cellphone : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="gjurl" class="col-sm-4 control-label">网址</label>
                <div class="col-sm-8">
                    <input type="text" id="gjurl" name="url" class="form-control"
                        value="{{ isset($trader) ? $trader->url : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-12 m-b">
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
    $(document).off('click','.country_openCon')
    $(document).on('click','.country_openCon',function () {
        //选择国家
        var th=$(this),
            outerTitle = '选择国家'
            api = '/admin/country/list';
        $.ajax({
            url: api,
            type: 'get',
            data:{
                action: 'html'
            },
            success: function (str) {
                if(str.code == 403){
                    layer.msg(str.msg)
                    return false
                }
                layer.open({
                    type: 1,
                    title: outerTitle,
                    area: '800px',
                    offset: 'top',
                    content: str,
                    btn: ['确定'],
                    yes: function (i,layero) {
                        let id = layero.find('.bg-active').data('id')
                            cusname = layero.find('.bg-active .customer_name').text()
                        if(th.attr('name') == 'destination_country'){
                            $('input[name=destination_country]').val(cusname)
                            $('input[name=destination_country]').parent().find('input[type=hidden]').val(id)
                        } else {
                            th.val(cusname)
                            th.parent().find('input[type=hidden]').val(id)
                        }
                        layer.close(i)
                    }
                })
            }
        })
    })
    $(document).off('click','.choose_Cus')
    $(document).on('click','.choose_Cus',function () {
        var ele,
            outerTitle = '选择客户'
            api = '/admin/customer/customer_choose';
        $.ajax({
            url: api,
            type: 'get',
            data: {
                action:'html'
            },
            success: function (str) {
                if(str.code == 403){
                    layer.msg(str.msg)
                    return false
                }
                var index = layer.open({
                    type: 1,
                    title: outerTitle,
                    area: '800px',
                    content: str,
                    btn: ['确定'],
                    offset: 'top',
                    yes: function (i) {
                        let id = ele.find('.bg-active').data('id')
                            cusname = ele.find('.bg-active .customer_name').text()
                        $('#cusName').val(cusname)
                        $('#customer_id').val(id)
                        layer.close(i)
                    }
                })
                ele = $('#layui-layer' + index + '')
            }
        })
    })
})
</script>
