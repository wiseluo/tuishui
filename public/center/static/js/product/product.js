$(document).on('click', '#product_tab li,#sceachpd', function () {
  $(this).addClass('am-active').siblings().removeClass('am-active')
  productTable(1)
})


//生成分页(需引用bootstrap-paginator.min.js)
function crepage(e, page, totalpage, getEvent) {
  var element = e
  totalpage = Math.ceil(totalpage / 10)
  element.bootstrapPaginator({
    bootstrapMajorVersion: 3, //bootstrap版本
    currentPage: page, //当前页面 
    numberOfPages: 7, //一页显示几个按钮（在ul里面生成5个li） 
    totalPages: totalpage != 0 ? totalpage : 1, //总页数
    itemTexts: function (type, page, current) {
      switch (type) {
        case "first":
          return "首页";
        case "prev":
          return "上一页";
        case "next":
          return "下一页";
        case "last":
          return "末页";
        case "page":
          return page;
      }
    },
    onPageClicked: function (event, originalEvent, type, page) {
      getEvent(page)
    }
  })
}

function productTable(page) {
  $.get('/api/product', {
    keyword: $("#pdkeyword").val(),
    status: $('#product_tab li.am-active a').data('type'),
    page: page,
    pageSize: 10,
    token: $.cookie('token')
  }, function (res) {
    // res = JSON.parse(res);
    var html = ''
    var k = 0;
    if (res.data != '') {
      data = res.data.data
      for (var i = 0; i < data.length; i++) {
        k = (page - 1) * 10 + i + 1;
        html += '<tr>' +
          '<td>' +
          '<small>' + k + '</small>' +
          '</td>' +
          '<td>' +
          '<small>' + data[i].name + '</small>' +
          '</td>' +
          '<td>' +
          '<small>' + (data[i].en_name) + '</small>' +
          '</td>' +
          '<td>' +
          '<small>' + data[i].hscode + '</small>' +
          '</td>' +
          '<td>' +
          '<small>' + data[i].tax_refund_rate + '%</small>' +
          '</td>' +
          '<td>' +
          '<small>' + data[i].number + '</small>' +
          '</td>' +
          '<td>' +
          '<small>' + data[i].status_str + '</small>' +
          '</td>' +
          '<td>' +
          '<small>' +
          '<a href="../../member/product/productDetail.html?id=' + data[i].id + '"><button type="button" class="am-btn am-btn-success am-btn-xs">详情</button></a>' +
          '<a style='+(data[i].status == 1||data[i].status == 4? 'margin-left:10px;': "display:none;")+' href="../../member/product/newProduct.html?id='+data[i].id+'&type=2><button type="button" class="am-btn am-btn-primary am-btn-xs">修改</button></a>' +
          '<button onclick="del('+data[i].id+')" type="button" style='+(data[i].status == 1? 'margin-left:10px;': "display:none;")+' class="del_btn am-btn am-btn-danger am-btn-xs">删除</button>' +
          '</small>' +
          '</td>' +
          '</tr>'
      }
      $('#product_list tbody').html(html);
      // console.log(res.data.currentPage)
      crepage($('#page'), res.data.currentPage, res.data.total, productTable)
    } else {
      $('#product_list tbody').html('<tr><td style="text-align:center;border-top:none; font-size:14px;" colspan="8">暂无数据</td></tr>');
    }
  })
}

function del(id){
  console.log(1)
  layer.confirm('是否确认要删除',function(){
    $.post('/api/product/delete/'+id,{
      token:$.cookie('token'),
    },function(res){
      if (data.status == 401) {
        layer.msg("请登录")
      }
      layer.msg(res.msg)
      productTable(1)
    })
  })
}