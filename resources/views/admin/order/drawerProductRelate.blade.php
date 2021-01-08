<link rel="stylesheet" href="{{ URL::asset('/css/amazeui.min.css') }}" type="text/css"/>
<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
<style media="screen">
    .bootstrap-table{
        padding: 10px 30px;
    }
</style>
<!--订单查看、修改、添加-->
<div class="proDetail">
    <form class="form-horizontal proForm" id="proForm" style="overflow: hidden; border-top:1px solid #cccccc">
        {{--1.商品报关信息--}}
        <div class="m-b m-l-sm" style="line-height: 19px;">
            <b class="badge bg-danger order_order">1</b>
            <label class="h5 text-danger m-l-xs">商品报关信息</label>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="name" class="col-sm-4 control-label necessary_front">产品名称</label>
                <div class="col-sm-8">
                    <input type="text" id="name" name="name" value="" class="form-control" placeholder="请填写产品名称" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">Hscode</label>
                <div class="col-sm-8">
                    <input type="text" id="hscode" name="hscode" value="" class="form-control" placeholder="请填写HSCode" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">报关金额</label>
                <div class="col-sm-8">
                    <input type="text" id="total_price" value="" class="form-control" placeholder="请输入报关金额" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-12 contract-con m-b">
            <div class="form-group">
                <label class="col-sm-1 necessary_front control-label">申报要素</label>
                <div class="col-sm-11">
                    <input type="text" value="" id="standard" name="standard" class="form-control" placeholder="请输入申报要素" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">产品数量</label>
                <div class="col-sm-8">
                    <input type="text" id="number" value="" class="form-control" placeholder="请输入产品数量" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">申报单位</label>
                <div class="col-sm-8">
                    <input type="text" placeholder="请选择申报单位" class="unit_openCon form-control" id="measure_unit_cn" value="" readonly />
                    <input type="hidden" id="measure_unit" name="measure_unit" value="" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">申报单价</label>
                <div class="col-sm-8">
                    <input type="text" id="single_price" value="" class="form-control" placeholder="申报单价" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="end_country_id" class="col-sm-4 control-label necessary_front">最终目的国</label>
                <div class="col-sm-8">
                    <input type="text" id="destination_country" class="form-control country_openCon" value="" placeholder="请选择最终目的国" readonly />
                    <input type="hidden" id="destination_country_id" class="form-control" value="" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="default_num" class="col-sm-4 control-label necessary_front">法定数量</label>
                <div class="col-sm-8">
                    <input type="text" value="" id="default_num" class="form-control" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="default_unit_id" class="col-sm-4 control-label necessary_front">法定单位</label>
                <div class="col-sm-8">
                    <input id="default_unit" class="unit_openCon form-control" placeholder="请选择法定单位" value="" readonly />
                    <input type="hidden" id="default_unit_id" value="" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        {{--2.商品退税信息--}}
        <div class="m-b m-l-sm" style="line-height: 19px;">
            <b class="badge bg-danger">2</b>
            <label class="h5 text-danger m-l-xs">商品退税信息</label>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">开票人名称</label>
                <div class="col-sm-8">
                    <input type="text" value="" id="company" class="form-control choose_drawer" placeholder="请选择开票人" readonly />
                    <input type="hidden" value="" id="drawer_id" name="drawer_id" class="form-control" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">英文品名</label>
                <div class="col-sm-8">
                    <input type="text" value="" id="en_name" name="en_name" class="form-control" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">境内货源地</label>
                <div class="col-sm-8">
                    <input class="domestic_source_con form-control" id="domestic_source" value="" placeholder="请选择境内货源地" readonly />
                    <input type="hidden" id="domestic_source_id" value="" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="broker_name" class="col-sm-4 control-label necessary_front">原产国(地区)</label>
                <div class="col-sm-8">
                    <input id="origin_country" class="country_openCon form-control" value="中国" placeholder="请选择原产国" readonly />
                    <input type="hidden" id="origin_country_id" value="774" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">货物属性</label>
                <div class="col-sm-8">
                    <select id="goods_attribute" class="form-control"  data-parsley-required data-parsley-trigger="change">
                        <option value="0">自产</option>
                        <option value="1">委托加工</option>
                        <option value="2">外购</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="unit_id" class="col-sm-4 control-label">单位</label>
                <div class="col-sm-8">
                    <input type="text" id="unit" class="form-control" value="" placeholder="请填写单位" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">净重(KG)</label>
                <div class="col-sm-8">
                    <input type="text" id="net_weight" class="form-control" value="" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">毛重(KG)</label>
                <div class="col-sm-8">
                    <input type="text" id="total_weight" class="form-control" value="" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">体积(CBM)</label>
                <div class="col-sm-8">
                    <input type="text" id="volume" class="form-control" value="" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">最大包装件数</label>
                <div class="col-sm-8">
                    <input type="text" id="pack_number" class="form-control" value="" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">退税率(%)</label>
                <div class="col-sm-8">
                    <input type="text" id="tax_refund_rate" name="tax_refund_rate" class="form-control" value="" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">税率</label>
                <div class="col-sm-8">
                    <input type="text" id="tax_rate" value="" class="form-control" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">开票金额</label>
                <div class="col-sm-8">
                    <input type="text" id="value" class="form-control" value="" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">享受优惠关税</label>
                <div class="col-sm-8">
                    <select id="enjoy_offer" class="form-control">
                        <option value="0">享受</option>
                        <option value="1">不享受</option>
                        <option value="2">不能确定</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">合并分组</label>
                <div class="col-sm-8">
                    <input type="text" id="merge" class="form-control" value="0" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">产品分类</label>
                <div class="col-sm-8">
                    <input type="text" id="pro_classification" class="form-control" value="" onclick="dataLayer(18,'产品分类',$(this))" readonly />
                    <input type="hidden" id="pro_classification_id" value="" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">品牌类型</label>
                <div class="col-sm-8">
                    <input type="text" id="type_str" class="form-control" value="" readonly />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">产地</label>
                <div class="col-sm-8">
                    <input type="text" id="production_place" class="form-control" value="" placeholder="请填写产地" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">CIQ编码</label>
                <div class="col-sm-8">
                    <input type="text" id="ciq_code" class="form-control" value="" placeholder="请填写CIQ编码" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">种类</label>
                <div class="col-sm-8">
                    <input type="text" id="species" class="form-control" value="" placeholder="请填写种类" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">表面材质</label>
                <div class="col-sm-8">
                    <input type="text" id="surface_material" class="form-control" value="" placeholder="请填写表面材质" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">款号</label>
                <div class="col-sm-8">
                    <input type="text" id="section_number" class="form-control" value="" placeholder="请填写款号" />
                </div>
            </div>
        </div>
    </form>
</div>
<script src="{{ URL::asset('/js/bootstrap-table.min.js') }}"></script>
<script src="{{ URL::asset('/js/bootstrap-table-zh-CN.min.js') }}"></script>
<script>
    $(function(){
        $(document).off('click','.domestic_source_con')
        $(document).on('click','.domestic_source_con',function () {  //选择境内货源地
            var th=$(this),
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
                    layer.open({
                        type: 1,
                        title: outerTitle,
                        area: '800px',
                        offset: 'top',
                        content: str,
                        btn: ['确定'],
                        yes: function (i,layero) {
                            let id = layero.find('.bg-active').data('id')
                                name = layero.find('.bg-active').data('name')
                            th.val(name)
                            th.parent().find('input[type=hidden]').val(id)
                            layer.close(i)
                        }
                    });
                }
            })
        })
        //开票人
        $(document).off('click','.choose_drawer')
        $(document).on('click','.choose_drawer',function () {  //选择客户
            let th = $(this)
                outerTitle = '选择开票人'
                html = `<table id="drawerListTable"></table>`
            layer.open({
                type: 1,
                title: outerTitle,
                area: '400px',
                offset: 'top',
                content: html,
                btn: ['确定'],
                yes: function (i,layero) {
                    let id = layero.find('.bg-active').children().eq(1).text()
                        name = layero.find('.bg-active').children().eq(0).text()
                    th.val(name)
                    th.parent().find('input[type=hidden]').val(id)
                    layer.close(i)
                },
                success: function (layero,index) {
                    getDrawerList(1)
                    $(document).off('click', '#drawerListTable tbody tr')
                    $(document).on('click', '#drawerListTable tbody tr', function () {
                        $(this).addClass('bg-active').siblings().removeClass('bg-active');
                    })
                    $(document).off('dblclick', '#drawerListTable tbody tr')
                    $(document).on('dblclick','#drawerListTable tbody tr',function(){
                        $(this).addClass('bg-active').siblings().removeClass('bg-active');
                        let id = layero.find('.bg-active').children().eq(1).text()
                            name = layero.find('.bg-active').children().eq(0).text()
                        th.val(name)
                        th.parent().find('input[type=hidden]').val(id)
                        layer.close(index)
                    })
                }
            })

        })
        function getDrawerList(page) {
            let api = '/admin/drawer/3'
                customer_id = $('#customer_id').val()
            $('#drawerListTable').bootstrapTable({
                url: api,
                cache: false, //是否需要缓存
                method: 'get',   //请求方式（*）
                pagination: true, //是否显示分页（*）
                sidePagination: "server",   //分页方式：client客户端分页，server服务端分页（*）
                pageSize: 10,   //每页的记录行数（*）
                pageNumber: 1,
                // pageList: [10],  //可供选择的每页的行数（*）
                paginationFirstText: "首页",
                paginationPreText: "上一页",
                paginationNextText: "下一页",
                paginationLastText: "末页",
                buttonsClass: 'btn',
                search: true,
                // showColumns: false, //是否显示所有的列（选择显示的列）
                //得到查询的参数
                queryParams : function (params) {
                    //这里的键的名字和控制器的变量名必须一直，这边改动，控制器也需要改成一样的
                    var pageNumber = $("#drawerListTable").bootstrapTable("getOptions").pageNumber
                    if(pageNumber == 'undefined'){
                        pageNumber = params.pageNumber;
                    }
                    var temp = {
                        keyword: params.search,
                        customer_id: customer_id,
                        pageSize: 10
                    };
                    console.log(temp,'temp');
                    return temp;
                },
                // responseHandler: function (res) { //有二级数据时重定向
                //     return res.attributes.name
                // },
                columns: [{
                    field: 'company',
                    width: 240,
                    title: '开票人名称',
                    class: 'company_name',
                    align: 'center',
                    valign: 'middle',
                }, {
                    field: 'id',
                    width: 240,
                    // visible: false,
                    title: 'id',
                    class: 'company_id',
                    align: 'center',
                    valign: 'middle',
                },],
                onLoadSuccess: function () {
                    $('.company_id').hide()
                },
                onLoadError: function () {
                    // showTips("数据加载失败！");
                },
            })

            // function refresh() {
            //     //刷新表格数据
            //     $('#drawerListTable').bootstrapTable('refresh');
            //     //$table.bootstrapTable('refresh'.{url:""});//刷新时调用接口防止表格无限销毁重铸时出现英文
            // }
        }
    });
</script>
