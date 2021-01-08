$(document).on('click', '#drawer_tab li,#sceachdw', function () {
  $(this).addClass('am-active').siblings().removeClass('am-active')
  drawerTable(1)
})

//生成分页(需引用bootstrap-paginator.min.js)
function crepage(e, page, totalpage, getEvent) {
  var element = e
  totalpage = Math.ceil(totalpage/10)
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

function drawerTable(page) {
  var status = $('#drawer_tab li.am-active a').data('type')
  $.get('/api/drawer', {
    // keyword:$('.search_txt').val(),
    status:status,
	  keyword:$("#dwkeyword").val(),
    page:page,
    pageSize:10,
    token:$.cookie('token')
  }, function (res) {
    res = res.data
    data = res.data
    if(data!=null&&data != ''){
      var html = ''
      for (var i = 0; i < data.length; i++) {
        html += '<tr>' +
          '<td>' +
          '<small>' + data[i].company+ '</small>' +
          '</td>' +
          '<td>' +
          '<small>' + data[i].tax_id + '</small>' +
          '</td>' +
          '<td>' +
          '<small>' + (data[i].created_at !== null ? data[i].created_at : '') + '</small>' +
          '</td>' +
          '<td>' +
          '<small>' +data[i].status_str+ '</small>' +
          '</td>' +
          '<td>' +
          '<small>' +
          '<a href="../../member/product/drawerDetail.html?id='+data[i].id+'&type=1"><button type="button" class="am-btn am-btn-success am-btn-xs">详情</button></a>' +
          '<a style='+(data[i].status == 1||data[i].status == 4? "margin-left:10px": "display:none;")+' href="../../member/product/createDrawer.html?id='+data[i].id+'&type=2><button type="button" class="am-btn am-btn-primary am-btn-xs">修改</button></a>' +
          '<a style='+(data[i].status == 3 ? "margin-left:10px": "display:none;")+' href="../../member/product/createDrawer.html?id='+data[i].id+'&type=2><button type="button" class="am-btn am-btn-primary am-btn-xs">关联</button></a>' +
          // '<button onclick="del('+data[i].id+')" type="button" style='+(data[i].status == 1? "margin-left:10px": "display:none;")+' class="del_btn am-btn am-btn-danger am-btn-xs">删除</button>' +
          '</small>' +
          '</td>' +
          '</tr>'
      }
      $('#drawer_table tbody').html(html)
	  crepage($('#page'), res.currentPage, res.total, drawerTable)
    }else {
      $('#drawer_table tbody').html('<tr><td style="text-align:center; border-top:none; font-size:14px;" colspan="5">暂无数据</td></tr>');
	    $('#page').html('');
    }

  })

}
function del(id){
  layer.confirm('是否确认要删除',function(){
    $.post('/api/drawerdelete/'+id,{
      token:$.cookie('token'),
    },function(res){
      layer.msg(res.msg)
      drawerTable(1)
    })
  })
}
