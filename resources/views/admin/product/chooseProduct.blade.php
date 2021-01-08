<!--选择产品-->
<link rel="stylesheet" href="">
<div class="choosePro panel-body">
    <div class="form-group" style="margin-left: 25px">
        <input type="hidden" class="choosePro_customer_id" value="{{$customer_id}}">
        {{--<label for="customerName" class="col-lg-4 control-label">产品名称</label>--}}
        <div class=" " style="width: 200px;">
            <input style="width: 200px;float: left" type="text" class="form-control drawer_product_kw" name="keyword" placeholder="输入产品名称">
        </div>
        <button onclick="GetEnterprise(1)" class="btn btn-success m-l-md">查询</button>
    </div>
    <div class="table-responsive panel m-t" style="width:100%;height: 450px; overflow-x:auto;">
        <table class="table table-hover b-t b-light" style="table-layout: fixed;height: 450px;">
            <thead>
                <tr class="info">
                    <th><input class="allCheck" type="checkbox" /></th>
                    <th width="13%">产品图片</th>
                    <th width="13%">产品名称</th>
                    <th width="13%">申请人</th>
                    <th width="13%">HSCode</th>
                    <th width="13%">型号</th>
                    <th width="13%">退税率</th>
                    <th width="13%">货号</th>
                </tr>
            </thead>
            <tbody class="bnoteTbody">

            </tbody>
        </table>
    </div>
    {{--分页--}}
    <div class="pagePicker">
        <div id="enterpriseOrder"></div>
    </div>
</div>
<script>
    $(function () {
      $(document).off('click', '.bnoteTbody tr')
      $(document).on('click', '.bnoteTbody tr', function () {
        // $(this).addClass('bg-active').siblings().removeClass('bg-active');
        if ($(this).children().find('[type=checkbox]').is(':checked')){
            $(this).children().find('[type=checkbox]').prop('checked',false)
        } else {
            $(this).children().find('[type=checkbox]').prop('checked',true)
        }
      })
      $(document).off('click', '.bnoteTbody [type=checkbox]')
      $(document).on('click', '.bnoteTbody [type=checkbox]', function () {
        // $(this).addClass('bg-active').siblings().removeClass('bg-active');
        if ($(this).is(':checked')){
            $(this).prop('checked',false)
        } else {
            $(this).prop('checked',true)
        }
      })
    })
    GetEnterprise(1)
    var dataObj = {
        page_enterprise : 1,
        page_order : 1,
        page_log: 1,
        page_log_info: 1
    }
    function GetEnterprise(page){
        var keyword = $('.drawer_product_kw').val()
        var html = ''
        $.get('/admin/product/product_choose',{keyword:keyword,page:page,action:'data',customer_id:$('.choosePro_customer_id').val()},function (res) {
            console.log(res);
            total = res.data.total
            $('.bnoteTbody').html("");
            $.each(res.data.data,function (a,b) {
                if(b.picture){
                    let img = b.picture.split('|')
                    picture = img[0]
                } else {
                    picture = ''
                }
                if(b.number == null) {
                    b.number = ''
                }
                html += '<tr data-id="' + b.id + '">' +
                        '<td><input class="check" type="checkbox" /></td>' +
                        '<td class=""><img src=' + picture + ' /><input type="hidden" class="imgSrc" value='+picture+' /></td>' +
                        '<td class="name">' + b.name + '</td>' +
                        '<td class="">' + b.created_user_name + '</td>' +
                        '<td class="hscode">' + b.hscode + '</td>' +
                        '<td class="standard">' + b.standard + '</td>' +
                        '<td class="">' + b.tax_refund_rate + '</td>' +
                        '<td class="number">' + b.number + '</td>' +
                    '</tr>';
            })
            $('.bnoteTbody').html(html);
            //调用分页
            layui.use(['laypage', 'layer'], function() {
                var laypage = layui.laypage,
                    layer = layui.layer;
                laypage.render({
                    elem: 'enterpriseOrder',
                    count: total,
                    first: '首页',
                    last: '尾页',
                    layout: ['count', 'prev', 'page', 'next', 'skip'],
                    curr: dataObj.page_enterprise,
                    theme: '#00A0E9',
                    jump:function(obj,first){
                        if(!first) {
                            //***第一次不执行,一定要记住,这个必须有,要不然就是死循环,哈哈
                            var curr = obj.curr;
                            //更改存储变量容器中的数据,是之随之更新数据
                            dataObj.page_enterprise = obj.curr;
                            //回调该展示数据的方法,数据展示
                            GetEnterprise(curr)
                        }
                    }
                });
            });
        })
    }

</script>
