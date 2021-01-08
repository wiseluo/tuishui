<style>
    .ptbody input{
        border:none;
        text-align: center;
        background: inherit;
    }
    .parsley-errors-list{
        margin-top:3px;
    }
    .form-group{
        margin-bottom: 15px !important
    }
</style>
<!--开票人查看、修改，新增-->
<div class="dradetail panel-body">
    <form class="form-horizontal" style="overflow: hidden;">
        <input type="hidden" name="status" class="status">
        <div class="form-group">
            <label for="" class="col-sm-3 control-label necessary_front">客户名称</label>
            <div class="col-sm-8">
                <input type="text" id="cusName" class="form-control choose_Cus" placeholder="点击选择客户" data-parsley-required data-parsley-trigger="change" value="{{ isset($drawer) ? $drawer->customer->name : ''}}" disabled/>
                <input type="hidden" value="{{ isset($drawer) ? $drawer->customer_id : ''}}" name="customer_id">
            </div>
        </div>
        <div class="form-group">
            <label for="drawerComName" class="col-sm-3 control-label necessary_front">开票人公司名称</label>
            <div class="col-sm-8">
                <input type="text" id="drawerComName" name="company" class="form-control" data-parsley-required data-parsley-trigger="change"  value="{{ isset($drawer) ? $drawer->company : ''}}" {{ $updateDoneDisabled }}/>
                <i class="fa fa-fw fa-search" style="position: absolute;top: 8px;right: 20px;" {{ $updateDoneDisabled }}></i>
            </div>
        </div>
        <div class="form-group">
            <label for="drawerPhone" class="col-sm-3 control-label necessary_front">开票人联系电话</label>
            <div class="col-sm-8">
                <input type="text" id="drawerPhone" name="telephone" class="form-control" data-parsley-required data-parsley-trigger="change"  value="{{ isset($drawer) ? $drawer->telephone : ''}}" {{ $updateDoneDisabled }}/>
            </div>
        </div>
        <!-- <div class="form-group">
            <label for="drawerTel" class="col-sm-3 control-label">座机</label>
            <div class="col-sm-8">
                <input type="text" id="drawerTel" name="phone" class="form-control" value="{{ isset($drawer) ? $drawer->phone : ''}}" {{ $updateDoneDisabled }}/>
            </div>
        </div>
        <div class="form-group">
            <label for="purchaser" class="col-sm-3 control-label">采购人</label>
            <div class="col-sm-8">
                <input type="text" id="purchaser" name="purchaser" class="form-control" value="{{ isset($drawer) ? $drawer->purchaser : ''}}" {{ $updateDoneDisabled }}/>
            </div>
        </div> -->
        <div class="form-group">
            <label for="taxIdtNumber" class="col-sm-3 control-label necessary_front">纳税人识别号</label>
            <div class="col-sm-8">
                <input type="text" id="taxIdtNumber" name="tax_id" class="form-control" placeholder="税务登记证上的号" data-parsley-required data-parsley-trigger="change"  value="{{ isset($drawer) ? $drawer->tax_id : ''}}" {{ $disabled }}/>
            </div>
        </div>
        <!-- <div class="form-group">
            <label for="bLRN" class="col-sm-2 control-label necessary_front">营业执照注册号</label>
            <div class="col-sm-8">
                <input type="text" id="bLRN" name="licence" class="form-control" data-parsley-required data-parsley-trigger="change"  value="{{ isset($drawer) ? $drawer->licence : ''}}" {{ $disabled }}/>
            </div>
        </div> -->
        <div class="form-group">
            <label for="taxIdtTime" class="col-sm-3 control-label necessary_front">一般纳税人认定时间</label>
            <div class="col-sm-8">
                <input type="text" id="taxIdtTime" onclick="laydate()"  name="tax_at" class="form-control" placeholder="此项需与提交的“一般纳税人认定书”照片信息保持一致" data-parsley-required data-parsley-trigger="change"  value="{{ isset($drawer) ? $drawer->tax_at : ''}}" {{ $updateDoneDisabled }}/>
            </div>
        </div>
        <div class="form-group">
            <label for="addressee" class="col-sm-3 control-label necessary_front">开票工厂收件人</label>
            <div class="col-sm-8">
                <input type="text" id="addressee" name="addressee" class="form-control" data-parsley-required data-parsley-trigger="change"  value="{{ isset($drawer) ? $drawer->addressee : ''}}" {{ $updateDoneDisabled }}/>
            </div>
        </div>
        <div class="form-group">
            <label for="raddress" class="col-sm-3 control-label necessary_front">开票工厂收件地址</label>
            <div class="col-sm-8">
                <input type="text" id="raddress" name="raddress" class="form-control" data-parsley-required data-parsley-trigger="change"  value="{{ isset($drawer) ? $drawer->raddress : ''}}" {{ $updateDoneDisabled }}/>
            </div>
        </div>
        <div class="form-group">
            <label for="drawerAddress" class="col-sm-3 control-label necessary_front">工厂生产地址</label>
            <div class="col-sm-8">
                <input type="text" id="drawerAddress" name="address" class="form-control" placeholder="即工厂地址，需和“税务登记证”地址一致，如不一致，需提供证明材料。" data-parsley-required data-parsley-trigger="change"  value="{{ isset($drawer) ? $drawer->address : ''}}" {{ $updateDoneDisabled }}/>
            </div>
        </div>
        <div class="form-group">
            <label for="domesticSource" class="col-sm-3 control-label necessary_front">境内货源地</label>
            <div class="col-sm-8">
                <input placeholder="请填写境内货源地" data-parsley-required data-parsley-trigger="change" class="domestic_source_con form-control" name="domestic_source" id="domestic_source" value="{{ isset($drawer) ? $drawer->domestic_source : ''}}" readonly {{ $updateDoneDisabled }}>
                <input type="hidden" id="domestic_source_id" name="domestic_source_id" value="{{ isset($drawer) ? $drawer->domestic_source_id : ''}}" {{ $updateDoneDisabled }}>
            </div>
        </div>
        <div class="form-group">
            <label for="tax_rate" class="col-sm-3 control-label necessary_front">开票人增值税率(%)</label>
            <div class="col-sm-8">
                <input type="text" id="tax_rate" name="tax_rate" class="form-control" data-parsley-required data-parsley-trigger="change"  value="{{ isset($drawer) ? $drawer->tax_rate : ''}}" {{ $updateDoneDisabled }}/>
            </div>
        </div>
        <div class="form-group">
            <label for="export" class="col-sm-3 control-label necessary_front">出口权</label>
            <div class="col-sm-8">
                <select id="export" class="form-control" data-parsley-required data-parsley-trigger="change" name="export" {{ $updateDoneDisabled }}>
                    <option value="0" {{ ($drawer->export==0) ? 'selected="selected"' : '' }}>有</option>
                    <option value="1" {{ ($drawer->export==1) ? 'selected="selected"' : '' }}>无</option>
                </select>
            </div>
        </div>
        @if($drawer->export == 0)
        <div class="form-group">
            <label for="customs_code" class="col-sm-3 control-label necessary_front">海关注册登记编码</label>
            <div class="col-sm-8">
                <input type="text" id="customs_code" name="customs_code" class="form-control" data-parsley-required data-parsley-trigger="change"  value="{{ isset($drawer) ? $drawer->customs_code : ''}}" {{ $updateDoneDisabled }}/>
            </div>
        </div>
        @endif
        @if($drawer->export == 1)
        <div class="form-group">
            <label for="tax_customs_code" class="col-sm-3 control-label necessary_front">税局备案海关代码</label>
            <div class="col-sm-8">
                <input type="text" id="tax_customs_code" name="tax_customs_code" class="form-control" data-parsley-required data-parsley-trigger="change"  value="{{ isset($drawer) ? $drawer->tax_customs_code : ''}}" {{ $updateDoneDisabled }}/>
            </div>
        </div>
        @endif
        <div class="form-group">
            <label class="col-sm-3 control-label">上传证照信息</label>
            <div class="col-sm-2">
                <a href="#" class="btn btn-s-md btn-primary necessary_front" data-upload="image" data-input="[name=pic_verification]">一般纳税人认定书</a>
                <input type="hidden" name="pic_verification" value="{{ isset($drawer) ? $drawer->pic_verification : ''}}" data-parsley-required data-parsley-trigger="change">
            </div>
            <div class="col-sm-2">
                <a href="#" class="btn btn-s-md btn-primary necessary_front" data-upload="image" data-input="[name=pic_register]">营业执照副本</a>
                <input type="hidden" name="pic_register" value="{{ isset($drawer) ? $drawer->pic_register : ''}}" data-parsley-required data-parsley-trigger="change">
            </div>
            <div class="col-sm-2">
                <a href="#" class="btn btn-s-md btn-primary necessary_front" data-upload="image" data-input="[name=pic_brand]">厂牌</a>
                <input type="hidden" name="pic_brand" value="{{ isset($drawer) ? $drawer->pic_brand : ''}}" data-parsley-required data-parsley-trigger="change">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-2">
                <a href="#" class="btn btn-s-md btn-primary necessary_front" data-upload="image" data-input="[name=pic_production]">生产线</a>
                <input type="hidden" name="pic_production" value="{{ isset($drawer) ? $drawer->pic_production : ''}}" data-parsley-required data-parsley-trigger="change">
            </div>
            <div class="col-sm-2">
                <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=pic_business_license]">税务登记证副本照片</a>
                <input type="hidden" name="pic_business_license" value="{{ isset($drawer) ? $drawer->pic_business_license : ''}}">
            </div>
            <div class="col-sm-4">
                <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=pic_home]">厂房租赁合同(或房产证)</a>
                <input type="hidden" name="pic_home" value="{{ isset($drawer) ? $drawer->pic_home : ''}}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-2 ">
                <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=pic_receipts_income]">进项发票</a>
                <input type="hidden" name="pic_receipts_income" value="{{ isset($drawer) ? $drawer->pic_receipts_income : ''}}">
            </div>
            <div class="col-sm-2 ">
                <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=pic_sales_invoice]">销项发票</a>
                <input type="hidden" name="pic_sales_invoice" value="{{ isset($drawer) ? $drawer->pic_sales_invoice : ''}}">
            </div>
            <div class="col-sm-2 ">
                <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=pic_credit_rating]">纳税信用等级</a>
                <input type="hidden" name="pic_credit_rating" value="{{ isset($drawer) ? $drawer->pic_credit_rating : ''}}">
            </div>
            <div class="col-sm-2 ">
                <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=pic_customs_certificate]">海关注册登记证书</a>
                <input type="hidden" name="pic_customs_certificate" value="{{ isset($drawer) ? $drawer->pic_customs_certificate : ''}}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-2 ">
                <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=tax_return]">纳税申报表</a>
                <input type="hidden" name="tax_return" value="{{ isset($drawer) ? $drawer->tax_return : ''}}">
            </div>
            <div class="col-sm-2 ">
                <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=pic_warehouse_photos]">仓库照片</a>
                <input type="hidden" name="pic_warehouse_photos" value="{{ isset($drawer) ? $drawer->pic_warehouse_photos : ''}}">
            </div>
            <div class="col-sm-3 ">
                <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=pic_other]">其他</a>
                <input type="hidden" name="pic_other" value="{{ isset($drawer) ? $drawer->pic_other : ''}}">
            </div>
        </div>
        @if('read' === request()->route('type') || 'relate_product' === request()->route('type') )
        <div class="form-group">
            <label class="col-sm-3 control-label necessary_front">产品信息</label>
            @if('relate_product' === request()->route('type'))
            <div class="col-sm-8">
                <a href="#" class="btn btn-s btn-danger addPro">添加产品</a>
                <input type="hidden" name="pid" class="pid">
            </div>
            @endif
        </div>
        <div class="form-group">
            <div class="table-responsive panel" style="overflow-x:auto;margin:0 15px;">
                <table class="table table-hover b-t b-light" style="table-layout: fixed;min-width: 800px;">
                    <thead>
                    <tr class="info">
                        <th width="10%">产品图片</th>
                        <th width="15%">产品名称</th>
                        <th width="13%">HSCode</th>
                        <th width="10%">型号</th>
                        <th width="10%">货号</th>
                        <th width="70px" class="text-center">操作</th>
                    </tr>
                    </thead>
                    <tbody class="ptbody">
                    @if (isset($drawer))
                        @foreach($drawer->product as $product)
                            {{--新增不显示，修改&查看&审核显示--}}
                            <tr data-id="{{ $product->id }}" >
                                <td><img src="{{ $product->picture_str }}" onclick="imgMagnify($(this).attr('src'))"></td>
                                <td>
                                    <input type="text" value="{{ $product->name }}" readonly>
                                </td>
                                <td>
                                    <input type="text" value="{{ $product->hscode }}" readonly>
                                </td>
                                <td>
                                    {{ $product->standard }}
                                </td>
                                <td>
                                    <input type="text" value="{{ $product->number }}" readonly>
                                </td>
                                @if (request()->route('type') === 'relate_product')
                                    <td class="text-center text-danger pbtn deleteBox" >删除</td>
                                @else
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach
                    @endif

                    </tbody>
                </table>
            </div>
        </div>
        @endif
        <div class="clearfix"></div>
        @include('member.common.approve', ['obj'=> isset($drawer) ? $drawer : ''])
    </form>
</div>
<script>
    $(function(){

        // 改变出口权显示
        $('.export_record').addClass('hide').children().find('input').attr('data-parsley-required',false)
        $(document).on('change','#export',function(){
          if($(this).val() == 0){
              $('.export_register').removeClass('hide').children().find('input').attr('data-parsley-required',true)
              $('.export_record').addClass('hide').children().find('input').attr('data-parsley-required',false)
          } else {
              $('.export_register').addClass('hide').children().find('input').attr('data-parsley-required',false)
              $('.export_record').removeClass('hide').children().find('input').attr('data-parsley-required',true)
          }
        })
        $("#export").change();

        $(document).off('click','.choose_Cus')
        $(document).on('click','.choose_Cus',function () {
            var ele,
                outerTitle = '选择客户'
                api = '/member/customer/customer_choose';
            $.ajax({
                url: api,
                type: 'get',
                data: {
                    action:'html'
                },
                success: function (str) {
                    var index = layer.open({
                        type: 1,
                        title: outerTitle,
                        area: '800px',
                        offset: 'top',
                        content: str,
                        btn: ['确定'],
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

        $(document).off('click','.fa-search')
        $(document).on('click','.fa-search',function(){ //选择开票人公司名称
            var ele,
                outerTitle = '选择开票人'
                api = '/admin/drawer/drawer_choose';
            $.ajax({
                url: api,
                type: 'get',
                data: {
                    action: 'html'
                },
                success: function (str) {
                    if(str.code == 403){
                        layer.msg(str.msg)
                        return false
                    }
                    var index = layer.open({
                        type: 1,
                        title: outerTitle,
                        area: '750px',
                        content: str,
                        btn: ['确定'],
                        yes: function (i) {
                            let id = ele.find('.bg-active').data('id')
                            $.ajax({
                                type: 'get',
                                url: '/admin/drawer/drawer_detail/' + id,
                                success: function (res) {
                                    if(res.code == 200){
                                        console.log(res);
                                        $.each(res.data,function(i,item){
                                            if(item == null){
                                                item = ''
                                            }
                                            $('#'+i).val(item)
                                        })
                                    } else {
                                        layer.msg(res.msg)
                                    }
                                }
                            })
                            layer.close(i)
                        }
                    })
                    ele = $('#layui-layer' + index + '')
                }
            })
        })
        $(document).off('click','.domestic_source_con')
        $(document).on('click','.domestic_source_con',function () {  //选择境内货源地
            var ele,th=$(this),
                outerTitle = '选择境内货源地'
            api = '/admin/district/list';
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
                    var index = layer.open({
                        type: 1,
                        title: outerTitle,
                        area: '800px',
                        offset: 'top',
                        content: str,
                        btn: ['确定'],
                        yes: function (i) {
                            let id = ele.find('.bg-active').data('id')
                                cusname = ele.find('.bg-active .customer_name').text()
                            th.val(cusname)
                            th.parent().find('input[type="hidden"]').val(id)
                            layer.close(i)
                        }
                    });
                    ele = $('#layui-layer' + index + '')
                }
            })
        })
        //      删除所选产品
        $('.ptbody').on('click','.deleteBox',function(){
            $(this).closest('tr').remove();
        })

        $(document).off('click','.addPro')
        $(document).on('click','.addPro',function () {
            var ele,
                outerTitle = '选择产品'
                api = '/member/product/product_choose';
            $.ajax({
                url: api,
                type: 'get',
                data:{
                    action: 'html',
                    customer_id: $('#customer_id').val()
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
                        yes: function (i) {
                            let obj = []
                                newTrs = ele.find('.bnoteTbody input:checked').closest('tr');
                            if(newTrs.length){
                                $.each(newTrs,function(i,item){
                                    obj = {
                                        id: $(this).data('id'),
                                        imgSrc: $(this).find('.imgSrc').val(),
                                        name: $(this).find('.name').text(),
                                        hscode: $(this).find('.hscode').text(),
                                        standard: $(this).find('.standard').text(),
                                        number: $(this).find('.number').text()
                                    }
                                    html =  `<tr data-id="${obj.id}" >
                                        <td><img src="${obj.imgSrc}" onclick="imgMagnify($(this).attr('src'))"></td>
                                        <td>
                                        <input type="text" value="${obj.name}" readonly>
                                        </td>
                                        <td>
                                        <input type="text" value="${obj.hscode}" readonly>
                                        </td>
                                        <td>${obj.standard}</td>
                                        <td>
                                        <input type="text" value="${obj.number}" readonly>
                                        </td>
                                        <td class="text-center text-danger pbtn deleteBox" >删除</td>
                                    </tr>`
                                    $('.ptbody').append(html)
                                })
                            }
                            layer.close(i)
                        }
                    })
                    ele = $('#layui-layer' + index + '')
                }
            })
        })
    })
</script>
