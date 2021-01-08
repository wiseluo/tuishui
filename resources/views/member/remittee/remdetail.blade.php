<!--收款方管理弹窗 :详情，新增-->
<style media="screen">
    #drawerListTable{
        width: 100%;
    }
    #drawerListTable tr{
        border-top: 1px solid #d3d3d3;
        border-left: 1px solid #d3d3d3;
    }
    #drawerListTable th,#drawerListTable td{
        border-right: 1px solid #d3d3d3;
        border-bottom: 1px solid #d3d3d3;
        text-align: center;
    }
</style>
<div class="remdetail">
    <form class="form-horizontal" style="overflow: hidden;">
        @include('member.common.dataselect', ['label'=>'收款单位类型', 'var'=>'remit_type', 'obj'=> isset($remittee) ? $remittee : '', 'col' => 6])
        <div class="col-sm-6 m-t">
            <div class="form-group">
                <label for="" class="col-sm-3 control-label necessary_front">收款单位</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($remittee) ? $remittee->tag : '' }}" class="form-control company" data-target="[name=remit_id]" data-outwidth="800px" data-parsley-required data-parsley-trigger="change" readonly/>
                    <input type="hidden" class="remit_id" name="remit_id" value="{{ isset($remittee) ? $remittee->remit_id : '' }}">
                </div>
            </div>
        </div>
        <div class="col-sm-6 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">收款户名</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($remittee) ? $remittee->name : '' }}" name="name" class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-6 m-t">
            <div class="form-group">
                <label for="" class="col-sm-3 control-label necessary_front">收款账号</label>
                <div class="col-sm-8">
                    <input type="text"  data-parsley-type="number"  value="{{ isset($remittee) ? $remittee->number : '' }}" name="number" class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-6 m-t">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">开户银行</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($remittee) ? $remittee->bank : '' }}" name="bank" class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(function(){
        //根据收款单位类型选择收款单位
        $('.company').off('click').on('click',function (){
            var ele,
                $this = $(this),
                outerWidth = $this.data().outwidth,
                outerTitle = $this.parent().prev().text();
            //开票工厂与订单号关联
            var company_type ;

            switch ($('#remit_type').val()){
                case 'App\\Customer':
                    company_type = 'customer';
                    $('.remit_id').attr('name','remit_id').prev().removeAttr('name');
                    choose_Cus()
                return false;
                case 'App\\Drawer':
                    company_type = 'drawer_user';
                    $('.remit_id').attr('name','remit_id').prev().removeAttr('name');
                    chooseDrawer()
                return false;
                case 'App\\Other':
                    $('.remit_id').removeAttr('name').prev().attr('name','remit_id');
                return;
            }
            var api = '/member/' + company_type + '/choose';
            $.get(api, {}, function (str) {
                var index = layer.open({
                    type: 1,
                    shadeClose: true,
                    title: outerTitle,
                    area: outerWidth,
                    offset: '100px',
                    btn: ['确定'],
                    content: str,
                    yes: function (i) {
                        var company_id = ele.find('.bg-active').data('id');
                        $($this.data('target')).val(company_id);
                        $this.val(ele.find('.bg-active td').eq(0).html());
                        layer.close(i);
                    },
                    end: function () {
                    }
                });
                ele = $('#layui-layer' + index + ' [data-gctable]');
                tableShow(ele);
            })
        });
        function chooseDrawer() {
            getDrawerList()
            layer.open({
                type: 1,
                shadeClose: true,
                title: '收款单位',
                area: '800px',
                offset: '100px',
                btn: ['确定'],
                content: `<div class="layerHtml">
                    <div class="panel-body">
                      <!-- <form class="form-inline queryForm gcForm m-b-sm"> -->
                      <div class="form-inline queryForm gcForm m-b-sm">
                          <div class="form-group">
                              <input type="text" class="form-control searchName" name="keyword" placeholder="开票人名称">
                          </div>
                          <button class="btn btn-success m-l-md search">查询</button>
                      </div>
                      <table id="drawerListTable">
                          <thead>
                              <tr>
                                  <th>开票人公司名称</th>
                                  <th>纳税人识别号</th>
                                  <th>客户名称</th>
                              </tr>
                          </thead>
                          <tbody class="list">

                          </tbody>
                      </table>
                      <div class="purpage page_turn" id="listPage"></div>
                  </div>
                </div>`,
                yes: function(i){
                    let name = $('.list .bg-active').find('td').eq(0).text()
                        id = $('.list').find('.bg-active').data('id')
                    $('.company').val(name)
                    $('.remit_id').val(id)
                    layer.close(i)
                }
            })
        }

        function getDrawerList(page) {
            var keyword = $('.searchName').val()
                html = ''
            if(keyword == undefined) {
                keyword = ''
            }
            $.ajax({
                url: '/member/drawer/3',
                data: {
                    pageSize: 10,
                    page: page,
                    keyword: keyword
                },
                dataType: 'json',
                success: function(res) {
                    $.each(res.rows,function(i,item){
                        html += `<tr class="drawer_choose" data-id="${item.id}">
                            <td>${item.company}</td>
                            <td>${item.tax_id}</td>
                            <td>${item.customer_name}</td>
                        </tr>`
                    })
                    let total = +res.total
                    console.log(total);
                    $('.list').html(html)
                    layui.use('laypage',function() {
                        var laypage = layui.laypage
                        laypage.render({
                            elem: 'listPage',
                            count: total,
                            groups: Math.ceil(total/10),
                            limit: 10,
                            curr:location.hash.replace('#!page=', ''),
                            hash: 'page', //自定义hash值
                            jump: function(obj,first){
                                if(!first) {
                                    //***第一次不执行,一定要记住,这个必须有,要不然就是死循环,哈哈
                                    var curr = obj.curr;
                                    //回调该展示数据的方法,数据展示
                                    getDrawerList(curr)
                                }
                            }
                        });
                    })
                }
            })
        }

        function choose_Cus(){
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
                            $('.company').val(cusname)
                            $('.remit_id').val(id)
                            layer.close(i)
                        }
                    })
                    ele = $('#layui-layer' + index + '')
                }
            })
        }
        $(document).on('click', '.drawer_choose', function () {
            $(this).addClass('bg-active').siblings().removeClass('bg-active');
        })
        $(document).off('dblclick','.drawer_choose')
        $(document).on('dblclick','.drawer_choose',function () {
            let name = $(this).find('td').eq(0).text()
                id = $(this).data('id')
            $('.company').val(name)
            $('.remit_id').val(id)
            layer.close(layer.index);
        })
        $(document).off('click','.search')
        $(document).on('click','.search',function () {
            location.hash = '#!page=1'
            getDrawerList()
        })

        //监听收款单位类型改变
        $('#remit_type').change(function () {
            $('.company').val('');
            if($(this).val() == 'App\\Other'){
                $('.company').removeAttr("readonly");
            }else{
                $('.company').attr("readonly","readonly");
            }
        })
    })

</script>
