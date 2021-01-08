<!--产品弹窗 :详情，修改，新增-->
<div class="prodetail">
    <form class="form-horizontal cusName" style="overflow: hidden;">
        <input type="hidden" name="status" class="status">
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="cusName" class="col-sm-4 control-label necessary_front">客户名称</label>
                <div class="col-sm-8">
                    <input type="text" id="cusName" class="form-control"
                           value="{{ isset($product->customer) ? $product->customer->name : ''}}" placeholder="点击选择客户"
                            data-target="[name=customer_id]" data-outwidth="800px"
                           data-parsley-required data-parsley-trigger="change" {{ $disabled }} readonly/>
                    <input type="hidden" name="customer_id" value="{{ isset($product) ? $product->customer_id : ''}}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="proName" class="col-sm-4 control-label necessary_front">产品名称</label>
                <div class="col-sm-8">
                    <input type="text" id="proName" name="name" class="form-control" data-parsley-required
                           data-parsley-trigger="change" value="{{ isset($product) ? $product->name : ''}}" {{ $disabled }} placeholder="输入完成按[回车]选择匹配"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="proEnName" class="col-sm-4 control-label">产品英文名称</label>
                <div class="col-sm-8">
                    <input type="text" id="proEnName" name="en_name" class="form-control"
                           value="{{ isset($product) ? $product->en_name : ''}}" {{ $updateDoneDisabled }} [readonly]="$('#proName').val == ''">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="proCode" class="col-sm-4 control-label necessary_front">HSCode</label>
                <div class="col-sm-8">
                    <input type="text" id="proCode" name="hscode" class="form-control" placeholder="输入完成按[回车]选择匹配" data-parsley-required data-parsley-trigger="change" data-parsley-type="number" value="{{ isset($product) ? $product->hscode : ''}}" {{ $updateDoneDisabled }}/>
                </div>
            </div>
        </div>
        @include('member.common.dataselect', ['label'=>'退税率(%)', 'var'=>'tax_refund_rate', 'obj'=>isset($product) ? $product : '', 'disabled'=> $updateDoneDisabled])
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="proNumber" class="col-sm-4 control-label">货号</label>
                <div class="col-sm-8">
                    <input type="text" id="proNumber" name="number" class="form-control"
                           value="{{ isset($product) ? $product->number : ''}}" {{ $updateDoneDisabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="proUnit" class="col-sm-4 control-label">法定单位</label>
                <div class="col-sm-8">
                    <input type="text" id="proUnit" name="unit" class="form-control"
                           value="{{ isset($product) ? $product->unit : ''}}" {{ $updateDoneDisabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label class="col-sm-4 control-label">关联品牌</label>
                <div class="col-sm-8">
                    <input type="text" class="brand form-control" value="{{ isset($product->brand) ? $product->brand->name : ''}}" disabled />
                    <!-- <input type="hidden" class="brand_id" name="brand_id" value="{{ isset($product->brand) ? $product->brand->id : ''}}" {{ $updateDoneDisabled }} /> -->
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">联系人</label>
                <div class="col-sm-8">
                    <input type="text" class="link form-control" value="{{ isset($product->link) ? $product->link->name : ''}}" data-parsley-required data-parsley-trigger="change" readonly disabled />
                    <!-- <input type="hidden" class="link_id" name="link_id" value="{{ isset($product->link) ? $product->link->id : ''}}" {{ $updateDoneDisabled }} /> -->
                </div>
            </div>
        </div>
        @include('member.common.dataselect', ['label'=>'申报单位', 'var'=>'measure_unit', 'obj'=> isset($product) ? $product : '', 'disabled'=> $updateDoneDisabled])
        <div class="clearfix"></div>
        <div class="col-sm-12 table-responsive">
            <table class="table table-bordered m-b-none">
                <thead class="hsDetailThead"></thead>
                <tbody class="hsDetailTbody"></tbody>
            </table>
        </div>
        <div class="col-sm-10 m-t">
            <div class="form-group">
                <label for="proPic" class="col-sm-2 control-label pic">图片</label>
                <div class="col-sm-3 img1">
                    <a href="#" id="picture" class="btn btn-s-md btn-primary necessary_front" data-upload="image" data-input="[name=picture]">产品图片</a>
                    <input type="hidden" name="picture" value="{{ isset($product) ? $product->picture : ''}}" data-parsley-required data-parsley-trigger="change" {{ $updateDoneDisabled }}>
                </div>
                <div class="col-sm-3 img1">
                    <a href="#" id="appearance_img" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=appearance_img]">产品整体外观图</a>
                    <input type="hidden" name="appearance_img" value="{{ isset($product) ? $product->appearance_img : ''}}" {{ $updateDoneDisabled }}>
                </div>
                <div class="col-sm-3 img1">
                    <a href="#" id="pack_img" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=pack_img]">产品内包装图</a>
                    <input type="hidden" name="pack_img" value="{{ isset($product) ? $product->pack_img : ''}}" {{ $updateDoneDisabled }}>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-3 img1">
                    <a href="#" id="customs_img" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=customs_img]">历史报关单</a>
                    <input type="hidden" name="customs_img" value="{{ isset($product) ? $product->customs_img : ''}}" {{ $updateDoneDisabled }}>
                </div>
                <div class="col-sm-3 img1">
                    <a href="#" id="brand_img" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=brand_img]">型号/品牌确认图</a>
                    <input type="hidden" name="brand_img" value="{{ isset($product) ? $product->brand_img : ''}}" {{ $updateDoneDisabled }}>
                </div>
                <div class="col-sm-3 img1">
                    <a href="#" id="other_img" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=other_img]">其他图片</a>
                    <input type="hidden" name="other_img" value="{{ isset($product) ? $product->other_img : ''}}" {{ $updateDoneDisabled }}>
                </div>
            </div>
        </div>
        <div class="col-sm-12 m-t">
            <div class="form-group">
                <label for="proNote" class="col-sm-1 control-label">备注</label>
                <div class="col-sm-11">
                    <textarea id="proNote" name="remark" class="form-control remarks" value="{{ isset($product) ? $product->remark : ''}}"
                              {{ $updateDoneDisabled }} rows="3"></textarea>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        @include('member.common.approve', ['obj'=>isset($product) ? $product : ''])
    </form>
</div>
<script>
    $(function () {
        if ($('.chooseGoodsBox').length == 0) {
            $(document.body).append('<div class="chooseGoodsBox" >' +
                '<div>' +
                '<input class="standard_val" placeholder="请输入搜索内容" style="margin-bottom: 15px" />' +
                '</div>' +
                '<table>' +
                '<thead>' +
                '<tr>' +
                '<th width="20%">HS编码</th>' +
                '<th width="25%">商品名称</th>' +
                '<th width="35%">品牌</th>' +
                '<th>税率</th>' +
                '<th>法定单位</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody class="goodsList">' +
                '</tbody>' +
                '</table>' +
                '<div class="pagePicker">'+
                '<div id="pro_page"></div>'+
                '</div>' +
                '</div>'
            )
        }
        $('#proCode,#proName').keyup(function (e) {
            e.stopPropagation();
            if (e.which === 13) {
                var $this = $(this);
                $('.standard_val').val('')
                getGoodsByEnter(1,$this.val().trim())
                layer.open({
                    type: 1,
                    title: '相关产品',
                    area: '1000px',
                    offset: '100px',
                    content: $('.chooseGoodsBox'),
                    btn: ['确定', '取消'],
                    yes: function (i) {
                        var number = $('.goodsList tr.active td:nth-child(1)').html(),
                            name = $('#proName').val(),
                            arr = $('.goodsList tr.active td:nth-child(3)').html();
                            Unit = $('.goodsList tr.active td:nth-child(5)').html();
                        $('#proUnit').val(Unit);
                        showStandard(number, name, arr);
                        layer.close(i);
                        $this.prop('disabled',false)
                    },
                    btn2: function(){
                        $this.prop('disabled',false)
                    },
                    cancel: function(){
                        $this.prop('disabled',false)
                    }
                });
                $this.prop('disabled',true)
                $("input").removeAttr("readonly");
                $('#cusName').attr("readonly","true");
            }
        });
        function showStandard(number, name, arr, disabled) {
            arr = arr.split('|');
            $.getJSON('/member/custom_sys/element', {
                hscode: number
            }).done(function(ret){
                var html1 = '<tr class="bg-gradient">',
                    html2 = '<tr>',
                    $thead = $('.hsDetailThead'),
                    $tbody = $('.hsDetailTbody');
                $.each(ret.data, function (i,item) {
                    html1 += '<th class="text-center">' + item + '</th>';
                    if(!arr[i]){
                      arr[i] = ''
                    }
                    html2 += '<td><input class="text-center" ' + disabled + ' name="standards[' + i + ']" type="text" value="'+arr[i]+'"></td> ';
                });
                $thead.html(html1);
                $tbody.html(html2);
                $('#proName').val(name);
                $('#proCode').val(number);
            });
        }
        var dataObj = {
            page_pro : 1,
            page_order : 1,
            page_log: 1,
            page_log_info: 1
        }
        function getGoodsByEnter(page,value) {
            if (value == '') {
                return false
            }
            let standard = $('.standard_val').val()
            $.getJSON('/member/custom_sys/product_list', {
                keyword: value,
                page: page
            }).done(function (res) {
                console.log(res);
                if (res.total === 0) {
            } else {
                var $goodsList = $('.goodsList'),
                    html = '';
                $.each(res.data.data, function (i, v) {
                    html += '<tr>' +
                        '<td>' + v.HSCODE + '</td>' +
                        '<td>' + v.PRODNAME + '</td>' +
                        '<td>' + v.PRODSP + '</td>' +
                        '<td>' + v.TAXRADIO + '</td>' +
                        '<td>' + v.UNIT + '</td>' +
                    '</tr>'
                });
                $goodsList.html(html);
                //调用分页
                layui.use(['laypage', 'layer'], function() {
                    var laypage = layui.laypage,
                        layer = layui.layer;
                    laypage.render({
                        elem: 'pro_page',
                        count: res.data.total,
                        first: '首页',
                        last: '尾页',
                        layout: ['count', 'prev', 'page', 'next', 'skip'],
                        curr: dataObj.page_pro,
                        theme: '#00A0E9',
                        jump:function(obj,first){
                            if(!first) {
                                //***第一次不执行,一定要记住,这个必须有,要不然就是死循环,哈哈
                                var curr = obj.curr;
                                //更改存储变量容器中的数据,是之随之更新数据
                                dataObj.page_pro = obj.curr;
                                //回调该展示数据的方法,数据展示
                                getGoodsByEnter(curr,value)
                            }
                        }
                    });
                });
                //相关商品搜索功能
                $(document).off('keyup','.standard_val')
                $(document).on('keyup','.standard_val',function(){
                    getGoodsByEnter(1,value)
                })
                }
            });
        }
        @if(in_array(request()->route('type'), ['read','approve']))
            showStandard('{{ $product->hscode }}', '{{ $product->name }}', '{{ $product->standard }}', 'disabled');
        @endif
        @if(in_array(request()->route('type'), ['update','update_done']))
            showStandard('{{ $product->hscode }}', '{{ $product->name }}', '{{ $product->standard }}');
        @endif
    });
    $('.chooseGoodsBox').on('click', '.goodsList tr', function () {
        $(this).addClass('active').siblings().removeClass('active');
    });
    $(document).ready(function(){
        $('#measure_unit').select2()
    })
</script>
