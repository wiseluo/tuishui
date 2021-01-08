
function getUrlParam(name) {
  var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
  var r = window.location.search.substr(1).match(reg); //匹配目标参数
  if (r != null) return unescape(r[2]);
  return undefined; //返回参数值
}
if ($.cookie('token') == undefined) {
  window.location.href = '/center/member/user/login.html'
}


$('body').append(`<div id="outerdiv" style="position:fixed;top:0;left:0;background:rgba(0,0,0,0.7);z-index:2;width:100%;height:100%;display:none;">
<div id="innerdiv" style="position:absolute;">
    <img id="bigimg" style="border:3px solid #fff;" src="" />
</div>
</div>`)

$(document).on('click', '.preview', function () {
  //点击放大
  var $this = $(this);
  imgShow("#outerdiv", "#innerdiv", "#bigimg", $this);
}).on('change', 'input[type=file]', function () {
  //图片上传
  $this = $(this)
  let imgList = ''
  var file_data = ''
  var formData = new FormData()
  for (var i = 0, f; i < this.files.length; i++) {
    file_data =  this.files[i];
    if($this.attr('accept') == 'image/*'){
        if (!/\.jpg$|\.png$|\.gif$|\.jpeg/i.test(file_data.name)){
            layer.msg('请上传正确图片格式')
            return false
        }
    }
    formData.append('token', $.cookie('token'))
    formData.append('fileList', file_data);

    $.ajax({
        type: 'POST',
        contentType: false,
        processData: false,
        mimeType: "multipart/form-data",
        url: '/api/upload',
        data: formData,
        success: function (data) {
            var data = $.parseJSON(data)
            var val = $this.next().val()
            if (data.code == 200) {
                layer.msg('上传成功')
                if(val != ''){
                    imgList = '|' + data.msg
                } else {
                    imgList = data.msg
                }
                $this.next().val(val + imgList)
                var html = ''
                jump = ''
                let http =  window.location.protocol
                let domain =  window.location.host
                let window_url = http + '//' + domain + data.msg
                if (/\.jpg$|\.png$|\.gif$|\.jpeg/i.test(data.msg)){
                    window_url =  http + '//' + domain + data.msg
                    jump = '<img class="preview" src="' + window_url + '" height="50">'
                    // console.log(window_url);
                } else if (/\.pdf/i.test(data.msg)) {
                    window_url = http + '//' + domain + '/images/center/pdf.png'
                    jump = '<a href="' + http + '//' + domain + data.msg +'" target="view_window"><img src="' + window_url + '" height="50" disabled></a>'
                }else if (/\.ppt/i.test(data.msg)) {
                    window_url = http + '//' + domain + '/images/center/ppt.png'
                    jump = '<a href="' + http + '//' + domain + data.msg +'"><img src="' + window_url + '" height="50"></a>'
                }else if (/\.doc$|\.docx/i.test(data.msg)) {
                    window_url = http + '//' + domain + '/images/center/doc.png'
                    jump = '<a href="' + http + '//' + domain + data.msg +'"><img src="' + window_url + '" height="50"></a>'
                } else if (/\.xls$|\.xlsx/i.test(data.msg)) {
                    window_url = http + '//' + domain + '/images/center/xls.png'
                    jump = '<a href="' + http + '//' + domain + data.msg +'"><img src="' + window_url + '" height="50"></a>'
                } else {
                    window_url = http + '//' + domain + '/images/center/zip.png'
                    jump = '<a href="' + http + '//' + domain +  data.msg +'"><img src="' + window_url + '" height="50"></a>'
                }

                html = '<div style="position:relative;margin-left:10px;display:inline-block">' + jump +
                '<span data-img="' + data.msg + '" style="position: absolute;right: -2px;opacity:1;color: #d20e0e;top:-6px;"' +
                ' data-am-modal-close class="am-close del_span">&times;</span></div>'
                $this.parent().next().append(html)
            } else {
                layer.msg('上传失败,原因：' + data.msg)
            }
        }
    })
  }
})

$(document).on('click', 'table tbody tr', function () {
  $(this).addClass('active').siblings().removeClass('active')
}).on('click', '.del_span', function () {
  var str = $(this).closest('li').find('input[type="hidden"]').val()
  $this = $(this)
  var newImg = [], imgsrc = ''
  if (str.indexOf('|') != -1) {
    str = str.split('|')
    str.map(function (item, index) {
      if ($this.data('index') === undefined) {
        if (item != $this.data('img')) {
          newImg.push(item)
        }
      } else {
        if (index != $this.data('index')) {
          newImg.push(item)
        }
      }
    })
    imgsrc = newImg.join('|')
  }

  $(this).closest('li').find('input[type="hidden"]').val(imgsrc)
  $(this).parent().remove()
})

//详情渲染图片
function creImg(str, className) {
    // console.log(str);
  if (str != '' && str != null) {
    var imgArr = [],
      html = ''
      jump = ''
    let http =  window.location.protocol
    let domain =  window.location.host
    let window_url = ''
    if (str.indexOf('|') != -1) {
      imgArr = str.split('|')
      imgArr.map(function (item, index) {
          if (/\.jpg$|\.png$|\.gif$|\.jpeg/i.test(item)){
              window_url = http + '//' + domain + item
              jump = '<img class="preview" src="' + window_url + '" height="50">'
          } else if (/\.pdf/i.test(item)) {
              window_url = http + '//' + domain + '/images/center/pdf.png'
              jump = '<a href="' + http + '//' + domain + item +'" target="view_window"><img src="' + window_url + '" height="50"></a>'
          }else if (/\.ppt/i.test(item)) {
              window_url = http + '//' + domain + '/images/center/ppt.png'
              jump = '<a href="' + http + '//' + domain + item +'"><img src="' + window_url + '" height="50"></a>'
          }else if (/\.doc$|\.docx/i.test(item)) {
              window_url = http + '//' + domain + '/images/center/doc.png'
              jump = '<a href="' + http + '//' + domain + item +'"><img src="' + window_url + '" height="50"></a>'
          } else if (/\.xls$|\.xlsx/i.test(item)) {
              window_url = http + '//' + domain + '/images/center/doc.png'
              jump = '<a href="' + http + '//' + domain + item +'"><img src="' + window_url + '" height="50"></a>'
          } else {
              window_url = http + '//' + domain + '/images/center/zip.png'
              jump = '<a href="' + http + '//' + domain + item +'"><img src="' + window_url + '" height="50"></a>'
          }
          html += '<div style="position:relative;margin-left:10px;display:inline-block">' + jump +
          '<span data-index="' + index + '" style="position: absolute;right: -2px;opacity:1;color: #d20e0e;top:-6px;"' +
          ' data-am-modal-close class="am-close del_span">&times;</span></div>'
      })
    } else {
        if (/\.jpg$|\.png$|\.gif$|\.jpeg/i.test(str)){
            window_url =  http + '//' + domain + str
            jump = '<img class="preview" src="' + window_url + '" height="50">'
        } else if (/\.pdf/i.test(str)) {
            window_url = http + '//' + domain + '/images/center/pdf.png'
            jump = '<a href="' + http + '//' + domain + str +'" target="view_window"><img src="' + window_url + '" height="50" disabled></a>'
        }else if (/\.ppt/i.test(str)) {
            window_url = http + '//' + domain + '/images/center/ppt.png'
            jump = '<a href="' + http + '//' + domain + str +'"><img src="' + window_url + '" height="50" disabled></a>'
        }else if (/\.doc$|\.docx/i.test(str)) {
            window_url = http + '//' + domain + '/images/center/doc.png'
             jump = '<a href="' + http + '//' + domain + str +'"><img src="' + window_url + '" height="50" disabled></a>'
        } else {
            window_url = http + '//' + domain + '/images/center/xls.png'
            jump = '<a href="' + http + '//' + domain + str +'"><img src="' + window_url + '" height="50" disabled></a>'
        }
        html = '<div style="position:relative;margin-left:10px;display:inline-block">' + jump +
        '<span data-index="0" style="position: absolute;right: -2px;opacity:1;color: #d20e0e;top:-6px;"' +
        ' data-am-modal-close class="am-close del_span">&times;</span></div>'
    }
    $('.' + className).next().append(html)
  }

}

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

function imgShow(outerdiv, innerdiv, bigimg, _this) {
  var src = _this.attr("src");//获取当前点击的pimg元素中的src属性
  $(bigimg).attr("src", src);//设置#bigimg元素的src属性
  /*获取当前点击图片的真实大小，并显示弹出层及大图*/
  $("<img/>").attr("src", src).load(function () {
    var windowW = $(window).width();//获取当前窗口宽度
    var windowH = $(window).height();//获取当前窗口高度
    var realWidth = this.width;//获取图片真实宽度
    var realHeight = this.height;//获取图片真实高度
    var imgWidth, imgHeight;
    var scale = 0.8;//缩放尺寸，当图片真实宽度和高度大于窗口宽度和高度时进行缩放

    if (realHeight > windowH * scale) {//判断图片高度
      imgHeight = windowH * scale;//如大于窗口高度，图片高度进行缩放
      imgWidth = imgHeight / realHeight * realWidth;//等比例缩放宽度
      if (imgWidth > windowW * scale) {//如宽度扔大于窗口宽度
        imgWidth = windowW * scale;//再对宽度进行缩放
      }
    } else if (realWidth > windowW * scale) {//如图片高度合适，判断图片宽度
      imgWidth = windowW * scale;//如大于窗口宽度，图片宽度进行缩放
      imgHeight = imgWidth / realWidth * realHeight;//等比例缩放高度
    } else {//如果图片真实高度和宽度都符合要求，高宽不变
      imgWidth = realWidth;
      imgHeight = realHeight;
    }
    $(bigimg).css("width", imgWidth);//以最终的宽度对图片缩放
    var w = (windowW - imgWidth) / 2;//计算图片与窗口左边距
    var h = (windowH - imgHeight) / 2;//计算图片与窗口上边距
    $(innerdiv).css({ "top": h, "left": w });//设置#innerdiv的top和left属性
    $(outerdiv).fadeIn("fast");//淡入显示#outerdiv及.pimg
  });

  $(outerdiv).click(function () {//再次点击淡出消失弹出层
    $(this).fadeOut("fast");
  });
}

(function ($) {
  $.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
      if (o[this.name]) {
        if (!o[this.name].push) {
          o[this.name] = [o[this.name]];
        }
        o[this.name].push((this.value != null ? this.value : '') || '');
      } else {
        o[this.name] = (this.value != null ? this.value : '') || '';
      }
    });
    return o;
  };
})(jQuery);
(function ($) {
  $.fn.serializeObjArr = function () {
    var orderItemArray = new Array();
    var a = this
    $.each(a, function () {
      var that = this;
      var orderItemObj = new Object();
      $(that).find("select,input").each(function () {
        var name = $(this).attr("name");
        name != undefined ? orderItemObj[name] = $(this).val() : ''
      });
      orderItemArray.push(orderItemObj);
    })
    return orderItemArray
  };
})(jQuery);
