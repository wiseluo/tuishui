$(document).on('click', '#brand_tab li,#sceachbd', function () {
  $(this).addClass('am-active').siblings().removeClass('am-active')
  brandTable(1)
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

function brandTable(page) {
  // console.log($.cookie('token'))
  $.get('/api/brand', {
    keyword:$("#bdkeyword").val(),
	status:$('#brand_tab li.am-active a').data('type'),
    page:page,
    pageSize:10,
    token:$.cookie('token')
  }, function (res) {
    data = res.data.data;
    var html = ''
    var k=0;

	if(data != ''){
    for (var i = 0; i < data.length; i++) {
		 k=(page-1)*10+i+1;
      html += '<tr id="bd'+(data[i].id)+'">' +
        '<td>' +
        '<small>' + k + '</small>' +
        '</td>' +
        '<td>' +
        '<small>' + data[i].name + '</small>' +
        '</td>' +
        '<td>' +
        '<small>' + (data[i].type_str) + '</small>' +
        '</td>' +
        '<td>' +
        '<small>' + (data[i].link_name) + '</small>' +
        '</td>' +
        '<td>' +
        '<small>' + data[i].status_str + '</small>' +
        '</td>' +
        '<td>' +
            '<small>' +
            '<a href="./brandDetail.html?id=' + data[i].id +
            '" data-title="11"><button type="button" class="am-btn am-btn-success am-btn-xs">详情</button></a>' +
            '<a class="editor" style='+(data[i].status == '1'||data[i].status == '4'? "margin-left:10px" : "display:none;")+' href="./newBrand.html?type=1&id=' + data[i].id +
            '" data-title="11"><button type="button" class="am-btn am-btn-primary am-btn-xs">编辑</button></a>' +
            '<a style='+(data[i].status == '1'? "margin-left:10px" : "display:none;")+' onclick="Delete('+data[i].id+')" data-title="11"><button type="button" class="am-btn am-btn-danger am-btn-xs">删除</button></a>' +
            '</small>' +
            '</td>' +
        '</tr>';
    }
	$('#brandList tbody').html(html);
	crepage($('#page'), res.data.currentPage, res.data.total, brandTable)

	}else{
		$('#brandList tbody').html('<tr><td style="text-align:center;border-top:none; font-size:14px;" colspan="6">暂无数据</td></tr>');
		$('#page').html('');
	}

  })
}

  function Delete (id){
	  if(confirm('确定要删除吗?')==false)return false;
     $.ajax({
      type: 'delete',
      url: '/api/brand/'+ id,
      data: {token:$.cookie('token')},
      success: function (data) {
        if (data.status == 401) {
          layer.msg("请登录")
        }
        if (data.code == 200) {
          alert(data.msg)
		  $("#brandList").find("#bd"+id).remove();
        }
      }
    })
  }
