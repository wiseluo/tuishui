<!--付款管理弹窗 :关联-->
<div class="paydetail">
    <form class="form-horizontal rem-ittee" style="overflow: hidden;">
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">客户</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($pay->customer->name) ? $pay->customer->name : '' }}" class="form-control" readonly />
                    <input type="hidden" class="customer_id" name="customer_id" value="{{ isset($pay->customer->id) ? $pay->customer->id : '' }}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">工作流</label>
                <div class="col-sm-8">
                    <select id="fid" class="form-control" data-parsley-required data-parsley-trigger="change" readonly disabled>
                        <option value="20" {{ (isset($pay) && $pay->fid==20) ? 'selected="selected"' : '' }}>业务报销(垫款)</option>
                        <option value="19" {{ (isset($pay) && $pay->fid==19) ? 'selected="selected"' : '' }}>业务报销(不垫款)</option>
                        <option value="198" {{ (isset($pay) && $pay->fid==198) ? 'selected="selected"' : '' }}>退税抵扣</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">申请日期</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($pay) ? $pay->applied_at : '' }}" id="strDate" readonly class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">款项类型</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($pay) ? $pay->type_str : '' }}" id="strDate" readonly class="form-control"/>
                    <input type="hidden" value="{{ isset($pay) ? $pay->type : '' }}" id="type" readonly class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">单据类型</label>
                <div class="col-sm-8">
                    <select id="yw_bills" class="form-control" data-parsley-required data-parsley-trigger="change" readonly disabled>
                        <option value="1" {{ (isset($pay) && $pay->yw_bills==1) ? 'selected="selected"' : '' }}>单据齐全</option>
                        <option value="2" {{ (isset($pay) && $pay->yw_bills==2) ? 'selected="selected"' : '' }}>无需单据</option>
                        <option value="3" {{ (isset($pay) && $pay->yw_bills==3) ? 'selected="selected"' : '' }}>后补单据</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">单据张数</label>
                <div class="col-sm-8">
                    <input type="text" id="dj_count" value="{{ isset($pay->dj_count) ? $pay->dj_count : '' }}" class="form-control" data-parsley-required readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <!-- 原经营单位 -->
                <label for="" class="col-sm-4 control-label necessary_front">报销公司</label>
                <div class="col-sm-8">
                    <input type="text" id="companyName" value="{{ isset($pay->company->name) ? $pay->company->name : '' }}" class="form-control choose" readonly />
                    <input type="hidden" class="company_id" name="company_id" value="{{ isset($pay) ? $pay->company_id : '' }}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">支付类型</label>
                <div class="col-sm-8">
                    <select id="yw_public" class="form-control" data-parsley-required data-parsley-trigger="change" readonly disabled>
                        <option value="1" {{ (isset($pay) && $pay->yw_public==1) ? 'selected="selected"' : '' }}>公司支付货款</option>
                        <option value="2" {{ (isset($pay) && $pay->yw_public==2) ? 'selected="selected"' : '' }}>私人支付货款</option>
                    </select>
                </div>
            </div>
        </div>

        {{--已付款查看页面可见--}}
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4  control-label necessary_front">付款日期 </label>
                <div class="col-sm-8">
                    <input type="text" id="endDate" value="{{ isset($pay) ? $pay->pay_at : '' }}" class="form-control" readonly />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">金额</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($pay) ? $pay->money : '' }}" class="form-control" readonly/>
                </div>
            </div>
        </div>
        <!-- <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">金额大写</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($pay) ? $pay->big_money : '' }}" class="form-control big_money" readonly/>
                </div>
            </div>
        </div> -->
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">币种</label>
                <div class="col-sm-8">
                    <input type="text" value="人民币" class="form-control mtype" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <!-- 原收款单位 -->
                <label for="" class="col-sm-4 control-label necessary_front moneny_dw">户名</label>
                <div class="col-sm-8">
                    <input type="text" id="remitteeName" value="{{ isset($pay->remittee->name) ? $pay->remittee->name : '' }}" class="form-control" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">收款账号</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($pay->remittee->number) ? $pay->remittee->number : '' }}" class="form-control sk_account" readonly />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">收款银行</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($pay->remittee->bank) ? $pay->remittee->bank : '' }}" class="form-control sk_bank" readonly />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">结汇余额</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($pay->customer->balance) ? $pay->customer->balance: ''}}" class="form-control balance" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">收汇余额</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($pay->customer->balance) ? $pay->customer->balance: ''}}" class="form-control balance" data-parsley-number readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">退税余额</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($pay->customer->tax_balance) ? $pay->customer->tax_balance: ''}}" class="form-control tax_balance" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">申请人</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($pay) ? $pay->apply_uname : '' }}" class="form-control apply_uname" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-9 m-t">
            <div class="form-group">
                <label for="" class="col-sm-2 control-label necessary_front">款项内容</label>
                <div class="col-sm-10">
                    <textarea class="form-control" readonly rows="3">{{ isset($pay) ? $pay->content : '' }}</textarea>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">业务类型</label>
                <div class="col-sm-8">
                    <select type="text" name="business_type" disabled class="form-control" data-parsley-required data-parsley-trigger="change">
                        <option value="1" {{ (isset($pay) && $pay->business_type==1) ? 'selected="selected"' : '' }}>出口业务</option>
                        <option value="2" {{ (isset($pay) && $pay->business_type==2) ? 'selected="selected"' : '' }}>进口业务</option>
                        <option value="3" {{ (isset($pay) && $pay->business_type==3) ? 'selected="selected"' : '' }}>代运业务</option>
                        <option value="4" {{ (isset($pay) && $pay->business_type==4) ? 'selected="selected"' : '' }}>代收代付</option>
                        <option value="5" {{ (isset($pay) && $pay->business_type==5) ? 'selected="selected"' : '' }}>平台服务</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="proPic" class="col-sm-4 control-label">附件</label>
                <div class="col-sm-5">
                    <input type="button" id="proPic" class="text-center form-control btn-s btn-primary" value="添加附件" data-upload="image"/>
                    <input type="hidden" value="{{ isset($pay) ? $pay->picture : '' }}">
                </div>
            </div>
        </div>
        @if(in_array(request()->route('type'), ['read', 'approve', 'update']))
            <div class="col-sm-4 m-t">
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label necessary_front">创建人</label>
                    <div class="col-sm-8">
                        <input type="text" value="{{ isset($pay) ? $pay->created_user_name : '' }}" class="form-control" readonly />
                    </div>
                </div>
            </div>
            @if(!empty($pay->approved_at))
                <div class="col-sm-4 m-t">
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label necessary_front">审核人</label>
                        <div class="col-sm-8">
                            <input type="text" value="{{ isset($pay) ? $pay->updated_user_name : '' }}" class="form-control"  readonly />
                        </div>
                    </div>
                </div>
            @endif
        @endif
        <div class="clearfix"></div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label class="col-sm-4 control-label">订单信息</label>
                <div class="col-sm-5">
                    <input type="button" class="text-center form-control btn-s btn-primary addOder" value="添加订单"/>
                </div>
            </div>
        </div>
        <div class="panel-body table-responsive panel" style="overflow-x:auto;width:100%;text-align: center">
            <table class="table table-hover b-t b-light order_listTable" style="table-layout: fixed;">
                <thead>
                    <tr>
                        <th width="80">订单号</th>
                        <th width="80">金额</th>
                        <th width="80">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($pay->order))
                        @foreach($pay->order as $key => $ord)
                        <tr class="j_order_list_item">
                            <input type="hidden" name="ord[{{ $loop->index }}][order_id]" value="{{$ord->pivot->order_id}}">
                            <input type="hidden" name="ord[{{ $loop->index }}][money]" value="{{$ord->pivot->money}}">
                            <td>{{$ord->ordnumber}}</td>
                            <td>{{$ord->pivot->money}}</td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label class="col-sm-4 control-label">收汇信息</label>
            </div>
        </div>
        <div class="panel-body table-responsive panel" style="overflow-x:auto;width:100%;text-align: center">
            <table class="table table-hover b-t b-light bill_listTable" style="table-layout: fixed;">
                <thead>
                    <tr>
                        <th width="80">收汇单号</th>
                        <th width="80">收汇金额</th>
                        <th width="80">付款金额</th>
                        <th width="80">实际收汇日期</th>
                        <th width="80">实际收汇金额</th>
                        <th width="80">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($pay->receipt))
                        @foreach($pay->receipt as $key => $receipt)
                        <tr class="j_order_list_item">
                            <input type="hidden" name="receipt[{{ $loop->index }}][receipt_id]" value="{{$receipt->pivot->receipt_id}}">
                            <td>{{$receipt->identifier}}</td>
                            <td>{{$receipt->rmb}}</td>
                            <td><input name="receipt[{{ $loop->index }}][pay_money]" value="{{$receipt->pivot->pay_money}}"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="clearfix"></div>
    </form>
</div>
 <script>
    $(function(data){
        $('.addOder').off('click').on('click',function () {  //添加订单信息
            var ele,
                $this = $(this),
                outerWidth = '600px'
                outerTitle = $this.parent().prev().text();
            var index = layer.open({
                type: 1,
                shadeClose: true,
                title: outerTitle,
                area: outerWidth,
                offset: '100px',
                // btn: ['确定'],
                content: `<div class="orderBox table-responsive panel" style="overflow-x:auto;margin:15px;">
                    <table class="table table-hover b-t b-light infoList" style="table-layout: fixed;">
                        <thead>
                            <tr>
                              <th>订单号</th>
                            </tr>
                        </thead>
                        <tbody class="orderList">
                        </tbody>
                        </table>
                    </div>
                <div class="purpage page_turn" id="page" style="text-align:center"></div>`,
                success: function(){
                    orderList()
                }
              // yes: function (i) {
              //     // $('#remitteeName').val(ele.find('.bg-active td').eq(1).html());
              //     // $('.sk_account').val(ele.find('.bg-active td').eq(3).html());
              //     // $('.sk_bank').val(ele.find('.bg-active td').eq(4).html());
              //     layer.close(i);
              // },
              // end: function () {
              // }
          });
        })
        dataObj.page_enterprise = 1
        function orderList(page){  //订单列表
            let ele,
                type = $('#type').val()
                customer_id = $('.customer_id').val()
                company_id = $('.company_id').val()
                api = '/member/finance/payorder/choose'
            $.ajax({
                type: 'get',
                url: api,
                data: {
                    page: page,
                    type: type,
                    customer_id: customer_id,
                    company_id: company_id
                },
                success: function(res){
                    if(str.code == 403){
                        layer.msg(str.msg)
                        return false
                    }
                    var html = ''
                    if(res.code == 200){
                        let total = res.data.total
                        if(res.data.data != '') {
                            $.each(res.data.data,function (i,v) {
                                html += `<tr class="order_choose">
                                <td>${v.ordnumber}</td>
                                <input type="hidden" class="id" value="${v.id}" />
                                </tr>`
                            })
                        } else {
                            html = `<tr class="">
                                <td>暂无可关联订单</td>
                            </tr>`
                        }
                        $('.orderList').html(html)
                        //调用分页
                        Creatpage('page',total, orderList)
                    }
                }
            })
        }

        $(document).off('dblclick','.order_choose')
        $(document).on('dblclick','.order_choose',function () {  //双击选择订单
            var orderHtml = ''
                order_index = $(".j_order_list_item").length;
            var orderNum = $(this).find('td').text();
                id = $(this).find('.id').val()
                orderHtml += `<tr class="j_order_list_item">
                        <td>${orderNum}</td>
                        <input name="ord[${order_index}][order_id]" type="hidden" class="id" value="${id}" />
                        <td><input name="ord[${order_index}][money]" class="price" /></td>
                        <td class="del" data-type="order">删除</td>
                    </tr>`
            $('.order_listTable tbody').append(orderHtml)
            layer.close(layer.index);
        })
        //删除当前商品信息
        $(document).on('click','.order_del',function () {
            $(this).closest('tr').remove();
            $(".j_order_list_item").each(function(index,item){
                $(item).find(".id").attr("name","ord["+index+"][id]");
                $(item).find(".price").attr("name","ord["+index+"][money]");
            });
            changePrice()
        })
        $(document).on('keyup','.price',function () {
            changePrice()
        })
        function changePrice() {
            var price = 0
            $('.j_order_list_item').find('.price').each(function(item,i){
                // $(item).find('.price').val()
                price += +$(this).val()
            })
            $('.money').val(price)
        }
    })
 </script>
