

/**
 * 传入比较的符号及数值,若格式不对则返回Null
 * @param v       {Object}  取数据对象
 * @param filter  {Array}  描述字符串
 * @returns {*}   {String}  返回 true 或 false
 */
function compare(v, filter) {
    if (!filter) {
        return true;
    }
    switch (filter) {
        case 'c':
        return v.status != 4 || v.status != 1; //查看：审批中&审批通过
        case 'u':
        return v.status == 1 || v.status == 4;//修改：草稿&审批拒绝
        case 'e':
        return v.status == 2;//审核：审批中
        case 'd':
        return v.status == 1; //删除：草稿
        case 'f':
        return v.status == 3; //结案：审批通过（订单）
        case 'x':
        return v.status == 3; //
        case 't':
        return v.status == 1 || v.status == 2;//申报管理 查看
        case 'z':
        return v.status == 1 || v.status == 4 || v.status == 3;//修改：草稿&审批拒绝
        case 'q':
        return v.status >= 1; //退税结算的查看
        case 's':
        return v.status == 1 || v.status == 4; //退税结算或审批拒绝修改
        case 'h':
        return v.status == 2;//退税结算的审核：审批中
        case 'g':
        return v.status == 3 && v.type == 0;//付款修改：审核通过关联订单
        case 'cz':
        return v.refundable == 2 || v.refundable == 3; //采购合同查看
        case 'ce':
        return v.refundable == 1; //采购合同审核
        default:
        throw new Error('参数不全');
    }
}
$(function () {
    //左侧导航栏激活样式
    var item = location.href.split('/')[3];
    if (!($('[name = ' + item + ']').hasClass('active'))) {
        $('[name = ' + item + ']').addClass('active').siblings().removeClass('active');
    }
    //tab页激活样式
    $('.nav-tabs a').each(function () {
        if ($(this).attr('href').split('/').reverse()[0] == location.href.split('/').reverse()[0]) {
            $(this).parent().addClass('active');
            $(this).parent().siblings().removeClass('active');
        }
    })
});

//调用分页
var dataObj = {
    page_enterprise : 1,
    page_order : 1,
    page_log: 1,
    page_log_info: 1
}
function Creatpage(e,total,method){  //e:分页ID，total：数据总条数，method：获取数据的方法
    // dataObj.page_enterprise = 1
    layui.use(['laypage', 'layer'], function() {
        var laypage = layui.laypage,
        layer = layui.layer;
        laypage.render({
            elem: e,
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
                    method(curr)
                }
            }
        });
    });
}

//  查看图片
function imgMagnify(imgUrl) {
    layer.open({
        type: 1,
        title: false,
        closeBtn: 2,
        area: '80%',
        offset: '10%',
        move: '.imgDrag',
        skin: 'layui-layer-nobg', //没有背景色
        content: '<div style="text-align: center">' +
            '<img  class="imgDrag" style="max-width:100%" src="' + imgUrl + '">' +
        '</div>'
    });
}

//  parsley验证
function validated($target) {
    var validated = true;
    $('[data-parsley-trigger]', $target).each(function () {
        $(this).parsley().validate();
        validated = $(this).parsley().isValid();
        if (!validated) {
            $(this).focus()
        }
        return validated;
    });
    return validated;
}

/**
 * 根据图片地址字符串渲染li
 * @param {Element}   target    显示容器的jquery对象
 * @param {String}    imgList   拼接图片地址字符串
 * @param {String}    glue      图片地址字符串分隔符 默认 '|'
 *
 * */
function randerImage(target, imgList, glue) {
    glue = glue || '|';
    if (!imgList) {
        return false;
    }
    // console.log(imgList);
    if (typeof imgList !== 'string') {
        return layer.alert('地址只能是字符串!');
    }
    imgList = imgList.split(glue);
    let imgSrc = ''
    let flag = true
    if($('.layui-layer-title').html() == '查看'|| $('.layui-layer-title').html()=='审核' ){
        flag = false
    } else {
        flag = true
    }
    for (var i = 0, html = ''; i < imgList.length; i++) {
        if (/\.jpg$|\.png$|\.gif$|\.jpeg/i.test(imgList[i])){
            imgSrc = '<img src="' + imgList[i] + '" data-src="' + imgList[i] + '">' +
                '<span class="gc_item_action"> ' +
                '<span class="gc_preview">预览</span> ' +
                ''+ (flag ? '<span class="gc_delete">删除</span>': '') +'' +
            '</span>'
        } else if (/\.pdf/i.test(imgList[i])) {
            window_url = '/images/center/pdf.png'
            imgSrc = '<img src="' + window_url + '" data-src="' + imgList[i] + '">' +
                '<span class="gc_item_action"> ' +
                '<span class="gc_preview pdf">预览</span>' +
                ''+ (flag ? '<span class="gc_delete">删除</span>': '') +'' +
            '</span>'
        } else if (/\.ppt/i.test(imgList[i])) {
            window_url = '/images/center/ppt.png'
        } else if (/\.doc$|\.docx/i.test(imgList[i])) {
          window_url = '/images/center/doc.png'
        } else if (/\.xls$|\.xlsx/i.test(imgList[i])) {
            window_url = '/images/center/xls.png'
        } else {
            window_url = '/images/center/zip.png'
        }
        if(!/\.jpg$|\.png$|\.gif$|\.jpeg$|\.pdf/i.test(imgList[i])){
            imgSrc = '<img src="' + window_url + '" data-src="' + imgList[i] + '">' +
                '<span class="gc_item_action">' +
                '<span class="gc_download">下载</span>' +
                ''+ (flag ? '<span class="gc_delete">删除</span>': '') +'' +
            '</span>'
        }
        html = '<li class="gc_item"> ' + imgSrc +'</li>'
        target.append(html);
    }
}


/* 上传图片函数封装 */
var all_img_len= 0;
function gc_upload_image(el) {
    var target = $($(el).data('input')),
        show = $('.gc_file_list'),
        tip = "图片格式不支持，请选择png/gif/bmp/jpeg/jpg格式的图片！", // 设定提示信息
        uploadUrl = '/admin/upload'; // 上传地址
    var s = target.val();
    var n = (s.split('/images/')).length;
    //console.log('22ddddddddddd'+n+"================"+s)
    if($('.hetong').index() !='-1') {
        if(n>10){
            layer.alert('最多上传10张图片！');
            return false;
        }
    }
    for (var i = 0, f; i < el.files.length; i++) {
        f = el.files[i];
        uploadingImage(f);
    }
    // 上传
    function uploadingImage(f) {
        var form = new FormData();
        form.append("fileList", f);// 文件对象
        // XMLHttpRequest 对象
        var xhr = new XMLHttpRequest();
        xhr.open("post", uploadUrl, true);
        xhr.onload = function () {
            let res = JSON.parse(xhr.responseText)
            if(res.code == 200) {
                var responseText = res.msg;
                var imgList = target.val();
                imgList += imgList == '' ? responseText : '|' + responseText;
                target.val(imgList);
                randerImage(show, responseText);
            } else {
                layer.msg(res.msg)
            }
        };
        xhr.send(form);
    }
}

/* 上传文件函数封装 */
function upload_file(el) {
    var target = $($(el).data('input')),
        tip, // 设定提示信息
        uploadUrl = '/admin/upload'; // 上传地址
    if ($(el).data('file') == 'excel') {
        tip = '文件格式不支持，请选择xls/xlsx格式文件';
        for (var i = 0, f; i < el.files.length; i++) {
            f = el.files[i];
            if (!/\.xls$|\.xlsx$/i.test(f.name)) {
                layer.msg(tip);
                // } else if (el.files[0].size > 1024 * 1024 * 2) {
                //   layer.msg('文件大小超限,请选择小于2M的文件!');
                //   el.value = null;
            } else {
                uploadingFile(f);
            }
        }
    } else {
        tip = "文件格式不支持，请选择png/gif/bmp/jpeg/jpg/pdf格式的文件,文件需小于2M！";
        for (var i = 0, f; i < el.files.length; i++) {
            f = el.files[i];
            if (!/\.jpg$|\.png$|\.gif$|\.bmp$|\.jpeg|\.pdf/i.test(f.name)) {
                layer.msg(tip);
                // } else if (el.files[0].size > 1024 * 1024 * 2) {
                //   layer.msg('文件大小超限,请选择小于2M的文件!');
            } else {
                uploadingFile(f);
            }
        }
    }

    // 上传
    function uploadingFile(f) {
        var form = new FormData();
        form.append("fileList", f);// 文件对象
        // console.log(222);
        // XMLHttpRequest 对象
        var xhr = new XMLHttpRequest();
        xhr.open("post", uploadUrl, true);
        xhr.onload = function () {
            var responseText = '/' + JSON.parse(xhr.responseText).msg;
            target.val(responseText).change();
        };
        xhr.send(form);
    }
}

/** 下载文件 文件名为最后一级地址名
 * @param {String} url  下载地址
 * @param {String} name 文件名
 * */
function downloadFile(url, name) {
  var a = document.createElement('a'),
    list = url.split('/');
  a.href = url;
  a.download = name || list[list.length - 1];
  $(document.body).append(a);
  a.click();
  a.parentNode.removeChild(a);
}

// 下载报关文件
$(document).on('click', '[data-download]', function () {
  var $this = $(this),
    url = $($this.data('download')).val();
  if (url === '') {
    return false;
  }
  downloadFile(url, $this.data('filename'))
});

//预览文件
$(document).on('click', '.preview', function () {
  var $this = $(this),
    url = $this.parent().find('.fileReader').val()
  if (url === '') {
    return false;
  }
  if (!/\.jpg$|\.png$|\.gif$|\.jpeg$|\.pdf/i.test(url)){
      let http =  window.location.protocol
      let domain =  window.location.host
      let window_url = http + '//' + domain + url
      console.log(window_url);
      layer.open({
          type: 2,
          content: 'https://view.officeapps.live.com/op/view.aspx?src=' + window_url,
          // content: 'https://view.officeapps.live.com/op/view.aspx?src=http://112.13.199.14:814/images/20190610/ihi0HXmjo0hONkfgVKlWg0BAehJ8GNUqOUK4mXNI.docx',
          area: ['1000px','650px']
      })
  } else {
      window.open(url)
  }
});

//上传文件
$(document).on('click', '[data-upload=file]', function () {
  $(this).next().trigger('click');
});

$(document).on('change', '.upload_file', function () {
  upload_file(this);
});

//  上传图片
$(document).on('click', '[data-upload=image]', function () {
  var $this = $(this);
  // console.log(111);
  /* 添加一个舞台 */
  $(document.body)
    .append('<div class="gc_upload_image"> ' +
      '<ul class="gc_file_list"></ul> ' +
      '<div class="gc_upload_btn"> ' +
      '<i class="gc_upload_icon">+</i> ' +
      '</div> ' +
      '<input multiple type="file" class="gc_upload_input" data-input="' + $this.data('input') + '"> ' +
      '</div>');
  var $box = $('.gc_upload_image');
  /* 首先根据input的值渲染图片*/
  randerImage($('.gc_file_list'), $($this.data('input')).val());
  $box.on('click', '.gc_upload_btn', function () {     //  上传按钮
      $(this).next().trigger('click');
    })
    .on('change', '.gc_upload_input', function () {  //  上传至服务器
    // var size = $('.gc_upload_input')[0].files[0].size;
    //  if(size >1024 * 1024 * 2){
    //      layer.alert('图片应小于2M');return false;
    //  }else{
    //  }
     gc_upload_image(this);

    })
    .on('click', '.gc_preview', function () {        //  预览
        if($(this).hasClass('pdf')){
            window.open($(this).parent().prev().data('src'))
        } else {
            imgMagnify($(this).parent().prev().data('src'));
        }
    })
    .on('click', '.gc_download', function () {        //下载
        var img = $(this).parent().prev(),
            url = img.data('src')
        window.location.href = url
    })
    .on('click', '.gc_delete', function () {         //  删除
        var img = $(this).parent().prev(),
            url = img.data('src'),
            imgInput = $($box.find('.gc_upload_input').data('input'));
        img.parent().remove();
        if (imgInput.val() != '') {
            var arrImg = imgInput.val().split('|');
            for (var i = 0; i < arrImg.length; i++) {
                if (arrImg[i] == url) {
                    arrImg.splice(i, 1);
                    break;
                }
            }
            imgInput.val(arrImg.join('|'));
            all_img_len--;
        }
    });
    /* 展示舞台 */
    layer.open({
        type: 1,
        content: $box,
        title: "添加图片",
        area: '680px',
        end: function () { //  弹窗被销毁时触发的回调
            $('.gc_upload_image').remove();
            if ($($this.data('input')).val() != '') {
                if ($this.data('change')) {
                    $this.addClass($this.data('change')).removeClass('btn-primary');
                }
                if ($this.data('changetext')) {
                    $this.text($this.data('changetext'));
                }
            } else {
                if ($this.data('change')) {
                    $this.removeClass($this.data('change')).addClass('btn-primary');
                }
                if ($this.data('changetext')) {
                    $this.text('上传图片');
                }
            }
        }
    });
});

// 设定日期区间查询,接收参数(起始input id, 结束input id)
function setDateRange(startId, endId) {

    startId = startId || 'apply_start_date';
    endId = endId || 'apply_end_date';
    var apply_start_date = {
        elem: '#' + startId,
        choose: function (datas) {
            apply_end_date.min = datas; //开始日选好后，重置结束日的最小日期
            apply_end_date.apply_start_date = datas; //将结束日的初始值设定为开始日
        }
    },
    apply_end_date = {
        elem: '#' + endId,
        choose: function (datas) {
            apply_start_date.max = datas; //结束日选好后，重置开始日的最大日期
        }
    };
    laydate(apply_start_date);
    laydate(apply_end_date);
}
var tableList = [];

/**
 *
 * @param $el {Element} 需要获取table自定属性的jQuery对象
 * @returns now         {Number}    创建时间戳
 * @returns url         {String}    api
 * @returns labelArr    {Array}     表头字段
 * @returns colWidthArr {Array}     列宽
 * @returns fieldArr    {Array}     数据字段
 * @returns fieldId     {String}    数据主键字段
 * @returns fieldLength {Number}    字段长度
 * @returns $queryForm  {Element}   查询form
 * @returns gcData      {String}    数据容器选择器
 * @returns gcPage      {String}    分页容器选择器
 * @returns operateArr  {Array[]}   行操作数组[[类型,操作名,按钮type,显示条件,api],...] 1: 页面层, 2: iframe层, 3: 询问框
 * @returns operateFilterArr  {Array}     行操作过滤器数组
 */
function getDataSet($el) {
    var obj = {},
        index = $.inArray($el[0], tableList);
    if (index !== -1) {
        // 存储格式 [el0,obj0,el1,obj1,...]
        obj = tableList[index + 1];
    } else {
        obj['now'] = +new Date();  //  创建时间戳
        obj['url'] = $el.data('url');  //  api
        obj['labelArr'] = $el.data('label').split(',');  //  表头字段
        obj['colWidthArr'] = $el.data('colwidth').split(',');  // 列宽
        obj['fieldArr'] = $el.data('field').split(',');  //  数据字段
        obj['fieldId'] = $el.data('id') || 'id'; // 数据主键字段
        obj['minWidth'] = $el.data('minwidth') || ''; // table最小宽度
        obj['save'] = $el.data('save'); // 保存表格数据
        obj['fieldLength'] = obj['fieldArr'].length;  // 字段长度
        obj['$queryForm'] = $($el.data('query')); // 查询form
        obj['gcData'] = '.gcData' + obj['now']; // 数据容器选择器
        obj['gcPage'] = '.gcPage' + obj['now']; // 分页容器选择器
        if ($el.data('operate')) {
            obj['operateArr'] = $el.data('operate').split(','); // 行操作
            obj['operateFilterArr'] = $el.data('operatefilter').split(','); // 行操作过滤器
            $.each(obj['operateArr'], function (i, v) {
                var arr = v.split('|');
                    obj['operateArr'][i] = {};
                    obj['operateArr'][i]['type'] = arr[0];
                    obj['operateArr'][i]['name'] = arr[1];
                    obj['operateArr'][i]['class'] = arr[2];
                    obj['operateArr'][i]['api'] = arr[3];
                    obj['operateArr'][i]['outwidth'] = arr[4];
                    obj['operateArr'][i]['filter'] = obj['operateFilterArr'][i];
                });
            obj['labelArr'].push('操作');
            obj['fieldArr'].push('operate');
            obj['colWidthArr'].push(obj['operateArr'].length * 30 + 70);
            obj['fieldLength']++;
        } else {
            obj['operateArr'] = null;
        }
        if ($el.data('check')) {
            obj['labelArr'].unshift('<input type="checkbox">');
            obj['fieldArr'].unshift('checkbox');
            obj['colWidthArr'].unshift(30);
            obj['fieldLength']++;
        }
        tableList.push($el[0], obj);
    }
    return obj;
}

/**
 *
 * @param $el   {Element} 表格jQuery对象
 * @param page  {Number}  请求页码
 */
//  创建全局对象 AllMData
var AllMData;
function getData($el, page) {
    var $data = getDataSet($el),
        symbol = $data.url.indexOf('?') == -1 ? '?' : '&',
        loadIndex = layer.load();
    $.getJSON($data.url + symbol + 'page=' + (page || '1') + '&pageSize=' + ($el.find('[class^=gcPage] select').val() || 10),
        $data.$queryForm.serialize()
    ).done(function (res) {
        var pageHtml = res.page || '暂无分页数据',
            dataHtml = '';
        // 判断是否有数据

        if (res.data.length === 0) {
            dataHtml = '<tr class="bg-white"><td colspan="' + $data.fieldLength + '">暂无数据</td></tr>'
        } else {
            $data.save && (window[$data.save] = res.data);  //全局创建数组，存储新数据
            //循环总数组条数
            AllMData=res.data;
            $.each(res.data, function (i, v) {
                dataHtml += '<tr class="bg-white" data-id="' + v[$data.fieldId] + '" data-tax_id="' + v.tax_id + '">';
                //循环当前的数组里的值
                $.each($data.fieldArr, function (j, field) {
                    try{
                        if (field === 'operate') {
                            dataHtml += '<td> <div class="btn-group">' +
                                (function () {
                                    var btnHtml = '';
                                    $.each($data.operateArr, function (k, btn) {
                                        compare(v, $data.operateFilterArr[k]) && (btnHtml += '<button type="button" data-id="' + v[$data.fieldId] + '" data-type="' + btn.type + '" data-api="' + btn.api + '" data-outwidth="' + btn.outwidth + '" class="btn btn-sm btn-' + (btn.class || 'default') + '">' + btn.name + '</button>')
                                    });
                                    return btnHtml;
                                })() +
                            '</div></td>'
                        } else if (field === 'checkbox') {
                        dataHtml += '<td><input type="checkbox"/></td>';
                        } else if (field.indexOf("picture") != -1) {
                            dataHtml += '<td><img src="' + eval('v.' + field) + '" onclick="imgMagnify($(this).attr(\'src\'))"></td>'
                        }
                        else {
                            //dataHtml += '<td>' + v[field] + '</td>'
                            dataHtml += '<td>' + eval('v.' + field) + '</td>'
                        }
                    }
                    catch(err){
                        dataHtml += '<td></td>'
                    }

                });
                dataHtml += '</tr>';

            });
        }
        $($data.gcData).html(dataHtml);
        $($data.gcPage).html(pageHtml);

    }).fail(function (err) {

    }).complete(function () {
        layer.close(loadIndex);
    })
}

var indexTr;  //订单管理添加产品的序号
(function ($) {
    $(function () {
        // 遍历gctable
        //  遍渲染表格框架
        $('[data-gctable]').each(function (i, v) {
            tableShow(v);
        });
        //  点击跳页
        $(document).on('click', '[data-gctable] [data-page]', function () {
            getData($(this).closest('[data-gctable]'), $(this).data('page'))
        }).on('keyup', '[data-gctable] .page_input', function (e) {
            //  输入跳页
            if (e.keyCode === 13) {
                var $this = $(this),
                    $parent = $this.closest('[data-gctable]'),
                    page = parseInt($this.val()) || 1,
                    max = $this.data('pagenum');
                if (page < 1) {
                    $this.val(1);
                    getData($parent);
                } else if (page > max) {
                    $(this).val(max);
                    getData($parent, max);
                } else {
                    getData($parent, page);
                }
            }
        })
      // 单击某行
        .on('click', '[data-gctable] .gcBody tr', function () {
            $(this).addClass('bg-active').siblings().removeClass('bg-active');
        })
      // 双击某行
        .on('dblclick', '[data-gctable] .gcBody tr', function () {
            $(this).addClass('bg-active').siblings().removeClass('bg-active');
            $(this).find('input[type="checkbox"]').attr("checked","checked");
            var t_par=$(this).parents('.layui-layer').find("a:contains('确定')");
            $(t_par).trigger('click');
        })
        // 切换显示条数重新获取数据
        .on('change', '[data-gctable] [class^=gcPage] select', function (e) {
            e.stopPropagation();
            getData($(this).closest('[data-gctable]'))
        })
        // data-type
        .on('click', '[data-type]', function (e) {
            e.stopPropagation();
            var $data = $(this).data();
            if ($data.type === 2) {
                getdetail($(this));
            } else {
                var func = window[getQuery('tp', $data.api)];
                func && func($data)
            }
        })
        //全选
        .on('click', '[data-gctable] thead th input[type=checkbox]', function (v) {
            $(".gcBody tbody td input[type=checkbox]").prop('checked', $(this).is(':checked'));
        })
    })
})(window.jQuery);

//渲染表格
function tableShow(v) {
    var $data = getDataSet($(v)),
        stageHtml = '';
    // 渲染舞台
    stageHtml += '<div ' + ($data.minWidth && ('style="min-width:calc(' + $data.minWidth + ' - 7px)"')) + '> ' +
        '<table> ' +
        '<colgroup> ' +
        (function () {
            var output = '';
            $.each($data.colWidthArr, function (i, v) {
                output += '<col width="' + Number(v) + '"> ';
            });
            return output;
        })() +
        '</colgroup> ' +
        '<thead> ' +
        '<tr> ' +
        (function () {
            var output = '';
            $.each($data.labelArr, function (i, v) {
                output += '<th>' + v + '</th> ';
            });
            return output;
        })() +
        '</tr> ' +
        '</thead> ' +
        '</table> ' +
        '</div>' +
        '<div class="gcBody" ' + ($data.minWidth && ('style="min-width:' + $data.minWidth + '"')) + '> ' +
        '<table> ' +
        '<colgroup> ' +
        (function () {
            var output = '';
            $.each($data.colWidthArr, function (i, v) {
                output += '<col width="' + Number(v) + '"> ';
            });
            return output;
        })() +
        '</colgroup> ' +
        '<tbody class="gcData' + $data.now + '"> ' +
        '</tbody> ' +
        '</table> ' +
        '</div>' +
        '<p class="gcPage' + $data.now + '"></p>';
    $(v).html(stageHtml);

    // 查询事件
    $data.$queryForm.submit(function (e) {
        e.preventDefault();
        getData($(v));
        return false;
    }).on('reset', function () {
        setTimeout(function () {
            getData($(v));
        }, 0)
    }).submit();
}
// 判断当前的请求是否成功，按照当前报错信息返回弹出提示
function ajaxerr(Http){
    var th422=Http.status;
    if(th422 == "422"){
        var th_error=Http.responseText;
        var obj2=eval("("+th_error+")");
        var sstr='';
        $.each(obj2,function (k) {
            console.log(k);
        });
        for(var i in obj2){
            sstr += obj2[i];
        }
        layer.msg(sstr, {icon:2}, function () {

        });
    } else if(th422 == "500"){
        layer.msg('出错了，请联系系统管理员', {icon:2}, function () {
        });
    }else if(th422 == "403"){
        var th_error3 =Http.getResponseHeader('msg');
        layer.msg($.parseJSON(th_error3));
    }
}
//订单页面数据交互
function order_lzc(checkUrl,logistics,outerDiv,outerTitle,pid,btn_content){
    $.ajax({
        type:"Get",
        url:checkUrl,
        data:{logistics:logistics},
        content_type: 'application/x-www-form-urlencoded;charset:utf-8',
        success:function (str)  {
            if(str.code == 403){
                layer.msg(str.msg)
                return false
            }
            layer.open({
                type: 1,
                title: outerTitle,
                area: outerWidth,
                offset: '50px',
                btn: btn_content,
                maxmin: true,
                content: str, //注意，如果str是object，那么需要字符拼接。
                success: function () {
                    indexTr = $('.otbody>tr').length;
                },
                yes: function (i, stage) {
                    if($("[name=logistics]").val()=="1"){
                        outerDiv = "con_orddetail"
                    }else {
                        outerDiv ="orddetail"
                    }

                    if (outerTitle != '审核') {
                        $('[name=status]').val(btn_content.length == 2 ? '1' : '2');
                    } else {
                        $('[name=status]').val($("." + outerDiv + " form .result").val());
                        if ($("." + outerDiv + " form .result").val() == '1') {
                            $("." + outerDiv + " form .opinion").attr({
                                'data-parsley-required': true,
                                'data-parsley-trigger': 'change'
                            })
                        }
                    }
                    console.log(outerDiv)
                    $('#start_port').change()
                    var flag = validated($("." + outerDiv + " form"));
                    //  如果判断没问题的话
                    if (flag && addPro(pid)) {
                        console.log(outerDiv)
                        var data = $("." + outerDiv + " form").serialize();
                        ad=$('[name="status"]').val()
                        ac=$('[name="opinion"]').val()
                        if(ad==4){
                            if(ac==''){
                                layer.msg('请填写审核意见！')
                            }else{
                                data = $("." + outerDiv + " form").serialize();
                                $.post(postUrl, data, function (res) {
                                    layer.msg('提交成功', {time: 1000}, function () {
                                        layer.close(i);
                                        getData($('[data-gctable]'));
                                        // location.reload();
                                        if(res.code == 403){
                                            layer.msg(res.msg)
                                        }
                                    });
                                })
                            }
                        }else{
                            data = $("." + outerDiv + " form").serialize();
                            $.post(postUrl, data, function (res) {
                                layer.msg('提交成功', {time: 1000}, function () {
                                    layer.close(i);
                                    getData($('[data-gctable]'));
                                    // location.reload();
                                    if(res.code == 403) layer.msg(res.code)
                                });
                            })
                        }
                    }
                },
                btn2: function (b) {
                    $('[name=status]').val('2');
                    var flag2 = validated($("." + outerDiv + " form"));
                    if (flag2 && addPro(pid)) {
                        var data = $("." + outerDiv + " form").serialize();
                        console.log(postUrl)
                        $.post(postUrl, data, function (res) {
                            layer.msg('提交成功', {time: 1000}, function () {
                                layer.close(b);
                                getData($('[data-gctable]'));
                                // location.reload();
                                if(res.code == 403) layer.msg(res.msg)
                            });
                        })
                    }
                    return false;
                },
                end: function () {
                }
            })
        }
    })
}

//打开增、改、查、审核弹窗
function getdetail(current) {
    console.log(111);
    var checkUrl = '',
    cdata = current.data(),
    pid = [],
    apiArr = cdata.api.split('?'),
    outerDiv = getQuery('outer', cdata.api), //最外层容器class
    outerWidth = cdata.outwidth,
    outerTitle = current[0].innerText || current.parent().prev().text(),
    searchStr = getQuery('tp', cdata.api), //判断类型
    df = getQuery('df', cdata.api) //判断是否需保存为草稿
    switch (searchStr) {
        case 'check':
                read = 'read/';
            break;
        case 'add':
                read = 'save/';
            break;
        case 'call':
                read = 'save/';
            break;
        case 'update':
                read = 'update/';
            break;
        case 'xiugai':
                read = 'update/';
            break;
        case 'examine':
                read = 'approve/';
            break;
        case 'ord':
                read = 'save/0?customer_id=';
            break;
        case 'relate':
                read = 'relate/';
            break;
        default:
                read = '';
    }
    switch (searchStr) {
        case 'add':
                post = '';
            break;
        case 'call':
                post = '';
            break;
        case 'ord':
                post = '';
            break;
        case 'update':
                post = '/update/';
            break;
        case 'xiugai':
                post = '/update/';
            break;
        case 'examine':
                post = '/approve/';
            break;
        case 'relate':
                post = '/relate/';
            break;
        default:
                post = '';
    }
    if (post != '') {
        post = post + cdata.id
    }
    // read = seasrchStr == 'check' ? 'read/' : ((searchStr == 'add' || searchStr == 'call') ? 'save/' : (searchStr == 'update' ? 'update/' : (searchStr == 'xiugai' ? 'update/' : (searchStr == 'examine' ? 'approve/' : (searchStr == 'ord' ? 'save/0?customer_id=' :''))))),
    // post = (searchStr == 'add' || searchStr == 'call'|| searchStr == 'ord') ? '' : '/update/' + cdata.id
    btn_content = searchStr == 'check' ? [] :  ['确定']; //类型为查看、修改、新增
    if (read) {

    if(current.data().api == "admin/order?outer=orddetail&tp=update"||current.data().api == 'admin/order?outer=orddetail&tp=examine'){
      checkUrl = '/' + apiArr[0] + '/' + read + cdata.id
      postUrl = '/' + apiArr[0] + post
      outerTitle = outerTitle
      order_lzc(checkUrl,logistics,outerDiv,outerTitle,pid,btn_content)
    }
    else{//正常流程
    checkUrl = '/' + apiArr[0] + '/' + read + cdata.id;//获取查看、修改、新增、审核弹窗
    postUrl = '/' + apiArr[0] + post; //提交url
    console.log(postUrl)
    // return false
    $.ajax({
      url:checkUrl,
      content_type: 'application/x-www-form-urlencoded;charset:utf-8',
      success:function (str)  {
        var AsessionA=$('#AsessionA').val();
          if(AsessionA ==""){
              layer.alert("该用户在ERP中不存在，请更换账号");
              return false;
          }
            layer.open({
                type: 1,
                title: outerTitle,
                area: outerWidth,
                offset: '50px',
                btn: btn_content,
                maxmin: true,
                content: str, //注意，如果str是object，那么需要字符拼接。
                success: function () {
                    indexTr = $('.otbody>tr').length;
                    var pay = $('.paydetail').index();
                    if(pay!==-1 && window.AllMData != undefined){
                        for(var i=0;i<window.AllMData.length;i++){
                            if(window.AllMData[i].id == cdata.id){
                                switch (window.AllMData[i].type){
                                    case '定金':
                                        $('#type').val('0');
                                        break;
                                    case '货款':
                                        $('#type').val('1')
                                        break;
                                    case '税金':
                                        $('#type').val('2');
                                        break;
                                    case '运费':
                                        $('#type').val('3');
                                        break;
                                    case '定金':
                                        $('#type').val('4');
                                        break;
                                }
                            }
                        }
                    }
                    var cus = $('.cuscus').index();

                    if(cus!== -1 && postUrl!== '/admin/customer'){
                      console.log(2222222)
                        for(var i=0;i<window.AllMData.length;i++){
                            window.AllMData[i].id == cdata.id ? $('#cusType').val(window.AllMData[i].custype) : '';
                            window.AllMData[i].id == cdata.id ? $('#billBase').val(window.AllMData[i].bill_base) : '';
                            if(window.AllMData[i].id == cdata.id) {
                                var a = window.AllMData[i].card_type - 1;
                            }
                        }
                        if($('#cusType').val()=='2'){
                            $('[name="personal"]').css('display','none');
                            $('[name="company"]').css('display','block');
                            $('[for="linkAddress"]').html('公司地址')
                        }else{
                            $('[name="personal"]').css('display','block');
                            $('[name="company"]').css('display','none');
                            $('[for="linkAddress"]').html('地址')
                        }
                        // $('#cusType').val(window.AllMData[i].custype);
                        // $('#principal').val(window.AllMData[i].principal);
                        // $('#cardType').val(window.AllMData[i].cardtype).select = selected;
                        // console.log(window.AllMData[i]);
                    }
                },
                yes: function (i, stage) {
                    if (outerTitle != '审核') {
                        $('[name=status]').val(btn_content.length == 2 ? '1' : '2');
                    } else {
                        $('[name=status]').val($("." + outerDiv + " form .result").val());
                        if ($("." + outerDiv + " form .result").val() == '1') {
                            $("." + outerDiv + " form .opinion").attr({
                                'data-parsley-required': true,
                                'data-parsley-trigger': 'change'
                            })
                        }
                    }
                    var flag = validated($("." + outerDiv + " form"));
                    //  如果判断没问题的话
                    if (flag && addPro(pid)) {
                        var data = $("." + outerDiv + " form").serialize();
                        ad=$('[name="status"]').val()
                        ac=$('[name="opinion"]').val()
                        if(ad==4){
                            if(ac==''){
                                layer.msg('请填写审核意见！')
                            }else{
                                $.post(postUrl, data, function () {
                                    layer.msg('提交成功', {time: 1000}, function () {
                                        layer.close(i);
                                        getData($('[data-gctable]'));
                                        //清除图片累加
                                        all_img_len = 0;

                                        // location.reload();
                                    });
                                }).error(function(XMLHttpRequest, textStatus, errorThrown,data) {
                                    ajaxerr(XMLHttpRequest);
                                });
                            }
                        }else{
                            //报关登记点击确定->待退税
                            if(current.data().api == "admin/filing?outer=fildetail&tp=call"){
                                var arr = data.split('&');
                                arr[0] = "status=1";
                                data = arr.join('&');
                            }
                            $.post(postUrl, data, function () {
                                layer.msg('提交成功', {time: 1000}, function () {
                                    layer.close(i);
                                    getData($('[data-gctable]'));
                                    //清除图片累加
                                    all_img_len = 0;
                                    // location.reload();
                                });
                            }).error(function(XMLHttpRequest, textStatus, errorThrown,data) {
                                ajaxerr(XMLHttpRequest,textStatus, errorThrown,data);
                            });
                        }
                    }
                },
                btn2: function (b) {
                     all_img_len = 0;
                    $('[name=status]').val('2');
                    var flag2 = validated($("." + outerDiv + " form"));
                    if (flag2 && addPro(pid)) {
                        var data = $("." + outerDiv + " form").serialize();
                        $.post(postUrl, data, function () {
                            layer.msg('提交成功', {time: 1000}, function () {
                                layer.close(b);
                                getData($('[data-gctable]'));
                                // location.reload();
                            });
                        }).error(function(XMLHttpRequest, textStatus, errorThrown,data) {
                            ajaxerr(XMLHttpRequest, textStatus, errorThrown,data);
                        });
                    }
                    return false;
                },
                end: function () {
                  // 重置当前图片数量
                  all_img_len = 0;
                }
            });}
    }).error(function(XMLHttpRequest, textStatus, errorThrown,data) {
        ajaxerr(XMLHttpRequest, textStatus, errorThrown,data);
    });}
  } else {
    //报关文件&&客户管理
    checkUrl = '/' + apiArr[0] + '/' + cdata.id;
    switch (searchStr){
      case 'checkFile':
        postUrl = '/admin/' + apiArr[0].split('/')[1] + '/update/' + cdata.id;
        break;
      case 'allot':
        postUrl = '/' + apiArr[0] + '/' + cdata.id;
        break;
      // case 'conTainEr':
      //   postUrl = '/admin/' + apiArr[0].split('/')[1] + '/container/' + cdata.id;
      //   break;
    }
    $.get(checkUrl, {}, function (str) {
      layer.open({
        type: 1,
        title: outerTitle,
        area: outerWidth,
        offset: '100px',
        btn: btn_content,
        content: str, //注意，如果str是object，那么需要字符拼接。
        yes: function (i) {
          var flag = validated($("." + outerDiv + " form"));
          if(flag){
            var data = $("." + outerDiv + " form").serialize();
            $.post(postUrl, data, function () {
              layer.msg('提交成功', {time: 1000}, function () {
                  // 分配客户
                  var blade=$('.btn_con').index();
                  if(blade != '-1'){
                      $('[data-gctable] input[type=checkbox]').each(function(){
                          $(this).attr("checked",false);
                      });
                  }
                layer.close(i);
                getData($('[data-gctable]'));
              });
            }).error(function(XMLHttpRequest, textStatus, errorThrown,data) {
                ajaxerr(XMLHttpRequest, textStatus, errorThrown,data);
            });
          }
        }
      });
    }).error(function(XMLHttpRequest, textStatus, errorThrown,data) {
        ajaxerr(XMLHttpRequest, textStatus, errorThrown,data);
    });
  }
}

//删除
function del(data) {
  var post = '/' + data.api.split('?')[0] + '/delete' + '/' + data.id;
        layer.confirm('确定要删除吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        /*if($('.order_order').index != -1){
            var customer_id;

            console.log(data.id)
        for( var i=0;i<window.AllMData.length;i++){
            console.log(window.AllMData[i].id);
            window.AllMData[i].id == data.id ? customer_id = window.AllMData[i].customer_id : console.log(data.id);
        }
        $.post(post,{data:customer_id}, function () {
            layer.msg('删除成功', {icon: 1,time: 1000}, function () {
                getData($('[data-gctable]'));
            });
        }).error(function(XMLHttpRequest, textStatus, errorThrown,data) {
            ajaxerr(XMLHttpRequest, textStatus, errorThrown,data);
        });
        }*/

            $.post(post,{id:data.id}, function () {
                layer.msg('删除成功', {icon: 1,time: 1000}, function () {
                    getData($('[data-gctable]'));
                });
            }).error(function(XMLHttpRequest, textStatus, errorThrown,data) {
                ajaxerr(XMLHttpRequest, textStatus, errorThrown,data);
            });

    }, function(){

    });

}

//发票资料
function xiazai(data){
    window.location.href='/admin/download/billing_data/' + data.id;
}

//下载报关资料
function generator(data){
    window.location.href='/admin/download/' + data.id;
}
//结案
function over(data) {
    layer.confirm('确定要结案吗？', {btn: ['确定','取消']}, function (i) {
        var post = '/' + data.api.split('?')[0] + '/close_case';
        $.post(post, {id: data.id}, function (res) {
            var result = JSON.parse(res);
            if(result.code == 200){
                getData($('[data-gctable]'));
                layer.close(i)
                layer.msg(result.msg,{time:1000})
            }else{
                layer.msg(result.msg,{time:1000})
            }
        }).error(function(XMLHttpRequest, textStatus, errorThrown,data) {
            ajaxerr(XMLHttpRequest, textStatus, errorThrown,data);
        });
    });
}

//特定页面特定修改按钮
// function xiugai(data) {
//     var cdata = data,
//         checkUrl = '',
//         pid = [],
//         apiArr = cdata.api.split('?'),
//         outerDiv = getQuery('outer', cdata.api), //最外层容器class
//         outerWidth = cdata.outwidth,
//         outerTitle = "修改"
//         searchStr = getQuery('tp', cdata.api), //判断类型
//         df = getQuery('df', cdata.api), //判断是否需保存为草稿
//         read = searchStr == 'check' ? 'read/' : ((searchStr == 'add' || searchStr == 'call') ? 'save/' : (searchStr == 'update' ? 'update/' : (searchStr == 'xiugai' ? 'update/' : (searchStr == 'examine' ? 'approve/' : (searchStr == 'ord' ? 'save/0?customer_id=' : ''))))),
//         post = (searchStr == 'add' || searchStr == 'call' || searchStr == 'ord') ? '' : '/update/' + cdata.id,
//         btn_content = searchStr == 'check' ? [] : (searchStr == 'add' || searchStr == 'xiugai' || df == 'y' ? ['保存草稿', '确定'] : ['确定']); //类型为查看、修改、新增
//     if (read) {
//         checkUrl = '/' + apiArr[0] + '/' + read + cdata.id; //获取查看、修改、新增、审核弹窗
//         postUrl = '/' + apiArr[0] + post; //提交url
//         $.get(checkUrl, {}, function (str) {
//             layer.open({
//                 type: 1,
//                 title: outerTitle,
//                 area: outerWidth,
//                 offset: '50px',
//                 btn: btn_content,
//                 maxmin: true,
//                 content: str, //注意，如果str是object，那么需要字符拼接。
//                 success: function () {
//                     indexTr = $('.otbody>tr').length;
//                 },
//                 yes: function (i, stage) {
//                     if (outerTitle != '审核') {
//                         $('[name=status]').val(btn_content.length == 2 ? '1' : '2');
//                     } else {
//                         $('[name=status]').val($("." + outerDiv + " form .result").val());
//                         if ($("." + outerDiv + " form .result").val() == '1') {
//                             $("." + outerDiv + " form .opinion").attr({
//                                 'data-parsley-required': true,
//                                 'data-parsley-trigger': 'change'
//                             })
//                         }
//                     }
//                     var flag = validated($("." + outerDiv + " form"));
//                     //  如果判断没问题的话
//                     if (flag && addPro(pid)) {
//                         var data = $("." + outerDiv + " form").serialize();
//                         ad = $('[name="status"]').val()
//                         ac = $('[name="opinion"]').val()
//                         if (ad == 4) {
//                             if (ac == '') {
//                                 layer.msg('请填写审核意见！')
//                             } else {
//                                 $.post(postUrl, data, function () {
//                                     layer.msg('提交成功', {time: 1000}, function () {
//                                         layer.close(i);
//                                         getData($('[data-gctable]'));
//                                         location.reload();
//                                     });
//                                 }).error(function(XMLHttpRequest, textStatus, errorThrown,data) {
//                                     ajaxerr(XMLHttpRequest);
//                                 });
//                             }
//                         } else {
//                             $.post(postUrl, data, function (res) {
//                               var delpro =  $.map(res, function(n){
//                                 return n.product.name
//                               }).join(',')
//                               if( res == ''){
//                               layer.msg('提交成功', {time: 1000}, function () {
//                                   layer.close(i);
//                                   getData($('[data-gctable]'));
//                                   location.reload();
//                               });}else {
//                                 layer.msg('您删除的'+delpro+'商品已经在其他订单中关联,无法删除,请重新操作',function(){
//                                   layer.close(i);
//                                 })

//                               }
//                             }).error(function (XMLHttpRequest, textStatus, errorThrown, data) {
//                                 ajaxerr(XMLHttpRequest);
//                             });
//                         }
//                     }
//                 },
//                 btn2: function (b) {
//                     $('[name=status]').val('2');
//                     var flag2 = validated($("." + outerDiv + " form"));
//                     if (flag2 && addPro(pid)) {
//                         var data = $("." + outerDiv + " form").serialize();
//                         $.post(postUrl, data, function (res) {
//                             var delpro =  $.map(res, function(n){
//                               return n.product.name
//                             }).join(',')
//                             if( res == ''){
//                             layer.msg('提交成功', {time: 1000}, function () {
//                                 layer.close(b);
//                                 getData($('[data-gctable]'));
//                                 location.reload();
//                             });}else {
//                               layer.msg('您删除的'+delpro+'商品已经在其他订单中关联,无法删除,请重新操作',function(){
//                                 layer.close(b);

//                               })

//                             }
//                         }).error(function (XMLHttpRequest, textStatus, errorThrown, data) {
//                             ajaxerr(XMLHttpRequest);
//                         });
//                     }
//                     return false;
//                 },
//                 end: function () {

//                 }
//             });
//         })
//             .error(function(XMLHttpRequest, textStatus, errorThrown,data) {
//                 ajaxerr(XMLHttpRequest);
//             });

//     }
// }

function getQuery(name, url) {
  var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
  var r = url.split('?')[1].match(reg);
  if (r != null)
    return unescape(r[2]);
  return null;
}

//选择客户,开票工厂(发票管理)
$(document).on('click', '[data-select]', function () {
    var api;
    var all_api=$(this).data().select;
    var regNumber = /\d+/; //验证0-9的任意数字最少出现1次。
        if(regNumber.test(all_api)){
            var spstr=  all_api.substr(all_api.lastIndexOf('/', all_api.lastIndexOf('/') ) );
            var v=all_api.replace(spstr,"");
            api = '/admin/'+v+'/choose'+spstr;
        } else{
            api =  '/admin/'+$(this).data().select+'/choose'
        }
    var ele,
        outerWidth = $(this).data().outwidth,
        $this = $(this),
        outerTitle = $(this).parent().prev().text();
    if ($(this).data().after) {
        $('[name=' + $(this).data().after + ']').val('').prev().val('');
    }
    var this_alt=$('.this_alt').index();
    var  pbulce='';
    if(this_alt =='0'){
        pbulce='bb2b';
    } else {
        pbulce=parseInt(10*Math.random());
    }
    var pendingRequests = {};
    jQuery.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
      var key = options.url;
      if (!pendingRequests[key]) {
        pendingRequests[key] = jqXHR;
      }else{
        //jqXHR.abort(); //放弃后触发的提交
        pendingRequests[key].abort(); // 放弃先触发的提交
      }
      var complete = options.complete;
      options.complete = function(jqXHR, textStatus) {
        pendingRequests[key] = null;
        if (jQuery.isFunction(complete)) {
          complete.apply(this, arguments);
        }
      };
    });
    $.get(api, pbulce, function (str) {
        if(str.code == 403){
            layer.msg(str.msg)
            return false
        }
        var index = layer.open({
            type: 1,
            title: outerTitle,
            area: outerWidth,
            offset: '100px',
            btn: ['确定'],
            content: str,
            yes: function (i) {
                var url = "/admin/filing/select/";
                var id = ele.find('.bg-active').data('id');
                // 退税页面
                var par = $('.goBshou').index();
                $par = $('.rebdetail');
                var inD = $par.find('.form-control');
                // 付款管理
                var remit = $('.panel-body.rem-Ite').index();
                var $remit = $('.rem-ittee').find('.form-control');
                $($this.data('target')).val(id);
                //  登记发票的纳税人编号
                $('#identify_number').val(ele.find('.bg-active').attr('data-tax_id'))
                // 退税页面
                if (par != '-1') {
                    $.get(url + id, '', function (data) {
                        $('.boxBody.con_Con').find('tr').remove();
                        var th = '';
                        var Da = data.invoices;
                        for (var i = 0; i < Da.length; i++) {
                            var mun = i + 1;
                            th += "<td>" + mun + "</td><td>" + Da[i].number + "</td>" +
                                "<td>" + i + 1 + " </td><td>" + i + 1 + "</td><td>" + Da[i].billed_at + "</td>" +
                                "<td>" + Da[i].sum + "</td><td>" + Da[i].received_at + "</td><td>" + Da[i].status_str + "</td>";
                            $('.boxBody.con_Con').append("<tr>" + th + "</tr>");
                            th = '';
                        }
                        $this.val(ele.find('.bg-active td').eq(0).html());
                        inD.eq(1).val(ele.find('.bg-active td').eq(1).html());
                        inD.eq(2).val(ele.find('.bg-active td').eq(2).html());
                        // id = data;
                        // $('#bill_head_id').val(id);
                    }).error(function (XMLHttpRequest, textStatus, errorThrown, data) {
                        ajaxerr(XMLHttpRequest);
                    });
                }
                // 付款管理
                // else if (remit != '-1') {
                //     if($(this)[0].title != "订单号" && $(this)[0].title != "客户"){
                //         $('.sk_unit').val(ele.find('.bg-active td').eq(1).html());
                //         $('.sk_account').val(ele.find('.bg-active td').eq(3).html());
                //         $('.sk_bank').val(ele.find('.bg-active td').eq(4).html());
                //     }
                // }
                else {
                  if( $this.attr('id') == 'district'){
                    $this.val(ele.find('.bg-active td').eq(1).html());
                    $('#district_code').val(ele.find('.bg-active td').eq(0).html())
                  }else{
                    $this.val(ele.find('.bg-active td').eq(0).html());
                    inD.eq(1).val(ele.find('.bg-active td').eq(1).html());
                    inD.eq(2).val(ele.find('.bg-active td').eq(2).html());}
                }
                layer.close(i);
            },
            end: function () {
            }
        });
        ele = $('#layui-layer' + index + ' [data-gctable]');
        tableShow(ele);
    }).error(function (XMLHttpRequest, textStatus, errorThrown, data) {
        ajaxerr(XMLHttpRequest);
    });

});
//公司选择
$(document).on('click', '.accdetail #company_name',function (obj) {
  var ele,
  $this = $(this),
  url = '',
  outerTitle = $this.parent().prev().text();
  var pendingRequests = {};
  jQuery.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
    var key = options.url;
    if (!pendingRequests[key]) {
      pendingRequests[key] = jqXHR;
    }else{
      //jqXHR.abort(); //放弃后触发的提交
      pendingRequests[key].abort(); // 放弃先触发的提交
    }
    var complete = options.complete;
    options.complete = function(jqXHR, textStatus) {
      pendingRequests[key] = null;
      if (jQuery.isFunction(complete)) {
        complete.apply(this, arguments);
      }
    };
  });
  var myId = $(this).attr('id');
  $.get('/admin/companies/choose',function (str) {
      if(str.code == 403){
          layer.msg(str.msg)
          return false
      }
      var index = layer.open({
        type: 1,
        title: outerTitle,
        area: '780px',
        offset: '100px',
        btn: ['确定'],
        content: str,
        yes: function (i) {
          var company_id = $('.company-tbody tr.bg-active td:eq(0)').text()
          $('.accdetail #company_id').val(company_id)
          var company_name = $('.company-tbody tr.bg-active td:eq(1)').text()
          $('.accdetail #company_name').val(company_name)
          layer.close(i);
        },
        end: function () {
        }
      });
    ele = $('#layui-layer' + index + ' [data-gctable]');
    tableShow(ele);
  }).error(function(XMLHttpRequest, textStatus, errorThrown,data) {
    ajaxerr(XMLHttpRequest);
  });
})

//账号选择
$(document).on('click', '.cusdetail #customer_user',function (obj) {
  var ele,
  $this = $(this),
  url = '',
  outerTitle = $this.parent().prev().text();
  var pendingRequests = {};
  jQuery.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
    var key = options.url;
    if (!pendingRequests[key]) {
      pendingRequests[key] = jqXHR;
    }else{
      //jqXHR.abort(); //放弃后触发的提交
      pendingRequests[key].abort(); // 放弃先触发的提交
    }
    var complete = options.complete;
    options.complete = function(jqXHR, textStatus) {
      pendingRequests[key] = null;
      if (jQuery.isFunction(complete)) {
        complete.apply(this, arguments);
      }
    };
  });
  var myId = $(this).attr('id');
  $.get('/admin/account/choose',function (str) {
      if(str.code == 403){
          layer.msg(str.msg)
          return false
      }
      var index = layer.open({
        type: 1,
        title: outerTitle,
        area: '780px',
        offset: '100px',
        btn: ['确定'],
        content: str,
        yes: function (i) {
          var customer_user_id = $('.account-tbody tr.bg-active td:eq(0)').text()
          $('.cusdetail #customer_user_id').val(customer_user_id)
          var customer_user = $('.account-tbody tr.bg-active td:eq(1)').text()
          $('.cusdetail #customer_user').val(customer_user)
          layer.close(i);
        },
        end: function () {
        }
      });
    ele = $('#layui-layer' + index + ' [data-gctable]');
    tableShow(ele);
  }).error(function(XMLHttpRequest, textStatus, errorThrown,data) {
    ajaxerr(XMLHttpRequest);
  });
})

//采购员选择 业务编号选择
$(document).on('click','#bnote_num',function (obj) {
  var ele,
  $this = $(this),
  url = '',
  outerTitle = $this.parent().prev().text();
  var pendingRequests = {};
  jQuery.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
    var key = options.url;
    if (!pendingRequests[key]) {
      pendingRequests[key] = jqXHR;
    }else{
      //jqXHR.abort(); //放弃后触发的提交
      pendingRequests[key].abort(); // 放弃先触发的提交
    }
    var complete = options.complete;
    options.complete = function(jqXHR, textStatus) {
      pendingRequests[key] = null;
      if (jQuery.isFunction(complete)) {
        complete.apply(this, arguments);
      }
    };
  });
  console.log(outerTitle)
  $.get('/admin/bnotelist',{action:'html'},function (str) {
      if(str.code == 403){
          layer.msg(str.msg)
          return false
      }
      var index = layer.open({
        type: 1,
        title: outerTitle,
        area: '780px',
        offset: '100px',
        btn: ['确定'],
        content: str,
        yes: function (i) {
            $('#bnote_num').val( $('.bnoteTbody tr.bg-active').children('td').eq('0').html())
            var bnote_id = $('.bnoteTbody tr.bg-active .bnote_id').html()
            $('#bnote_id').val(bnote_id )
            $.get('/admin/bnoteinfo/'+ bnote_id,function (res) {
              var data = JSON.parse(res).data
              $('#shipping_at').val(data.bnote_floaddate)  //预计装柜日期
              $('#packing_at').val(data.bnote_rloaddate)  //装柜日期
              $('#customs_at').val(data.bonte_customstime)  //报关日期
              $('#sailing_at').val(data.bnote_fsailingdate)  //开船日期
              if(data.bnote_sportid == '0'){
                layer.msg('起运港不存在')
              }else{
                $('#start_port').val(data.bnote_sport)  //起运港
                $('#shipment_port').val(data.bnote_sportid)  //起运港select的value值
              }
              $('#end_country').val(data.bnote_ecountry)  //最终目的国
              $('#aim_country').val(data.bnote_ecountryid)  //最终目的国id

              if(data.bnote_eportid == '0'){
                layer.msg('抵运港不存在')
              }
              else{
                $('#unloading_port').val(data.bnote_eportid)  //抵运港select的value值
                $('#end_port').val(data.bnote_eport)  //抵运港
              }
              $('#box_number').val(data.bnote_sealingnum)   //货柜箱号
              $('#box_type').val(data.bnote_boxtype)   //货柜箱型
              $('#tdnumber').val(data.bnote_billnum)  //货柜提单号
              $('#clearance_mode').val("一般贸易")  //报关形式
              $('#broker_name').val(data.bnote_declarname)  //报关行名称
              $('#broker_number').val(data.broker_number)  //报关行代码(10位)
              $('#ship_name').val(data.bnote_voyagecount)  //船名
              $('#operator').val(data.bnote_operator)  //操作人
              $('#is_pay_special').val('0')
              $('#is_special').val('0')
              $("#transport option:contains("+data.bnote_transportstyle+")").attr("selected", true);  //运输形式
              $('#transport_i').val($('#transport').val()) //运输形式select的value值
              if(data.message){
                $("#clearance_port option:contains("+data.message.export_port+")")?
                $("#clearance_port option:contains("+data.message.export_port+")").attr("selected", true):layer.msg('报关口岸不存在');
                $('#clearance_port_i').val($('#clearance_port').val())  //报关口岸
                $('#white_card').val(data.message.white_cardnum)   //白卡号
                $('#plate_number').val(data.message.service)   //车牌号
              }else{
                layer.msg('报关信息不全')
              }
            })
          layer.close(i);
        },
        end: function () {
        }
      });
    // ele = $('#layui-layer' + index + ' [data-gctable]');
    // tableShow(ele);
  }).error(function(XMLHttpRequest, textStatus, errorThrown,data) {
    ajaxerr(XMLHttpRequest);
  });
})



//选择订定金单号以及自动补全
// $(document).on('click', '#payNum[data-followed]', function (obj) {
//     var ele,
//         $this = $(this),
//         outerWidth = $this.data().outwidth,
//         outerTitle = $this.parent().prev().text();
//     //开票工厂与订单号关联
//     var drawer_id = $('[name=' + $(this).data().before + ']').val();
//     if (!drawer_id) {
//         layer.msg('请先选择用户！');
//         return false;
//     } else {
//         var api = '/admin/' + $this.data().followed + '/choose/'+ drawer_id;
//     }
//     var pendingRequests = {};
//     jQuery.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
//       var key = options.url;
//       if (!pendingRequests[key]) {
//         pendingRequests[key] = jqXHR;
//       }else{
//         //jqXHR.abort(); //放弃后触发的提交
//         pendingRequests[key].abort(); // 放弃先触发的提交
//       }
//       var complete = options.complete;
//       options.complete = function(jqXHR, textStatus) {
//         pendingRequests[key] = null;
//         if (jQuery.isFunction(complete)) {
//           complete.apply(this, arguments);
//         }
//       };
//     });
//     $.get(api, {}, function (str) {
//         var cccc=str;
//         // var a=0;
//         var index = layer.open({
//             type: 1,
//             title: outerTitle,
//             area: outerWidth,
//             offset: '100px',
//             btn: ['确定'],
//             content: str,
//             yes: function (i) {
//                 var order_id = ele.find('.bg-active').data('id');
//                 var a = $this.parent().parent().index();
//                 $($this.data('target')).val(order_id);
//                 for(var j=0;j<$('#payNum').length;j++){
//                     if(ele.find('.bg-active td').eq(0).html() == $('.pay_number')[j].value){
//                         layer.msg('该定金单号已使用');
//                         return false;
//                     }
//                 }
//                 $('.pay-id')[a].value= order_id;
//                 $this.val(ele.find('.bg-active td').eq(0).html());
//                 layer.close(i);
//             },
//             end: function () {
//             }
//         });
//         ele = $('#layui-layer' + index + ' [data-gctable]');
//         tableShow(ele);
//     }).error(function(XMLHttpRequest, textStatus, errorThrown,data) {
//         ajaxerr(XMLHttpRequest);
//     });
// });


//选择订单号以及自动补全（发票管理）
// $(document).on('click', '[data-follow]', function () {
//   var ele,
//     $this = $(this),
//     outerWidth = $this.data().outwidth,
//     outerTitle = $this.parent().prev().text(),
//     pclass = $(this).data().content,
//     ptbody = $(pclass);
//   //开票工厂与订单号关联
//   var drawer_id = $('[name=' + $(this).data().before + ']').val(),
//       follow_id = $(this).data().before
//   if (!drawer_id) {
//     follow_id == 'customer_id'?layer.msg('请先选择客户！'):layer.msg('请先选择开票工厂！');
//     return false;
//   } else {
//     var api = '/admin/drawer_product/choose/'+ drawer_id;
//   }
//   var pendingRequests = {};
//   jQuery.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
//     var key = options.url;
//     if (!pendingRequests[key]) {
//       pendingRequests[key] = jqXHR;
//     }else{
//       //jqXHR.abort(); //放弃后触发的提交
//       pendingRequests[key].abort(); // 放弃先触发的提交
//     }
//     var complete = options.complete;
//     options.complete = function(jqXHR, textStatus) {
//       pendingRequests[key] = null;
//       if (jQuery.isFunction(complete)) {
//         complete.apply(this, arguments);
//       }
//     };
//   });
//   $.get(api, {}, function (str) {
//     var cccc=str;
//     var index = layer.open({
//       type: 1,
//       title: outerTitle,
//       area: outerWidth,
//       offset: '100px',
//       btn: ['确定'],
//       content: str,
//       yes: function (i) {
//         if(follow_id != 'customer_id'){
//           var order_id = ele.find('.bg-active').data('id'),
//             bpi = '/admin/clearance/' + order_id;
//           adtt= '/admin/data/father/' + 13;
//           $($this.data('target')).val(order_id);
//           $this.val(ele.find('.bg-active td').eq(0).html());
//           $.get(bpi, {}, function (v) {
//             $('.invBody').html('').append(addPTr(v));
//             if(v.generator !== null){
//               var strs =  v.generator.split("|");
//
//               var lis = $.map(strs, function (item) {
//                 return '<li> <img src="'+item+ '"alt="图片"> </li>';
//               }).join('');
//               $('#dowebok').html(lis);
//
//               $('#contract').show();
//             }
//
//             var jincieryi=$('.jincieryi').index();
//             if(jincieryi<0){
//               layer.msg('当前订单未开票数量不足，请重新选择订单');
//               $("input[data-before='drawer_id']").val('');
//             }
//             //设定当前的order.id
//             $this.parent().find('.conorder').val(v.order.id);
//             var th = $('.huiv');
//             $.get(adtt, {}, function (data) {
//               var par= th;
//               var con='';
//               for(var i in data){
//                 con+="<option value="+data[i]+">" + data[i] + "</option>";
//               }
//               par.html("").append(con);
//             });
//           });
//         } else {
//           ele.find('input:checked').closest('tr').each(function () {
//             pclass == '.otbody' && ptbody.append(addOTr(this.dataset.id));
//           });
//           ele.find('tr.bg-active').each(function(){
//             pclass == '.stbody' && ptbody.append(addSTr(this.dataset.id));
//           })
//         }
//         layer.close(i);
//       },
//       end: function () {
//       }
//     });
//     ele = $('#layui-layer' + index + ' [data-gctable]');
//     tableShow(ele);
//   }).error(function(XMLHttpRequest, textStatus, errorThrown,data) {
//       ajaxerr(XMLHttpRequest);
//   });
// });

//选择产品（开票人管理&&订单管理&&申报登记）
$(document).on('click', '.btn[data-check]', function () {
    var ele, $this = $(this),s_data = window.AllMData;
    api = '/admin/' + $(this).data().check + '/choose',
    outerWidth = $(this).data().outwidth,
    pclass = $(this).data().content,
    ptbody = $(pclass),
    outerTitle = $(this).parent().prev().text();
    if($('.tsjs').index() !=-1){
        var customer_id = $('.settlement_id').val();
        if (!customer_id) {
            layer.msg('请先选择用户！');
            return false;
        } else {
            var api = '/admin/' + $this.data().check + '/choose/'+ customer_id;
        }
    }
    var pendingRequests = {};
    jQuery.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
        var key = options.url;
        if (!pendingRequests[key]) {
            pendingRequests[key] = jqXHR;
        }else{
        //jqXHR.abort(); //放弃后触发的提交
            pendingRequests[key].abort(); // 放弃先触发的提交
        }
        var complete = options.complete;
        options.complete = function(jqXHR, textStatus) {
            pendingRequests[key] = null;
            if (jQuery.isFunction(complete)) {
                complete.apply(this, arguments);
            }
        };
    });
    $.ajax({
      url:api,
      success:function(str,data){
          if(str.code == 403){
              layer.msg(str.msg)
              return false
          }
          var index = layer.open({
              type: 1,
              title: outerTitle,
              area: outerWidth,
              offset: '100px',
              btn: ['确定'],
              content: str,
              yes: function (i) {
                  ele.find('input:checked').closest('tr').each(function () {
                      pclass == '.ptbody' && ptbody.append(addTr(this.dataset.id));
                      pclass == '.otbody' && ptbody.append(addOTr(this.dataset.id));
                      pclass == '.ftbody' && ptbody.append(addFTr(this.dataset.id));
                      pclass == '.pertbody' && ptbody.append(addPETr(this.dataset.id));
                      pclass == '.roltbody' && ptbody.append(addPETr(this.dataset.id));
                  });
                  ele.find('tr.bg-active').each(function(){
                      pclass == '.stbody' && ptbody.append(addSTr(this.dataset.id));
                  })
                  layer.close(i);
                  //申报登记
                  // var url ="/admin/filing/batch/";
                  // var num = $('.form-control.fxxkU').val();
                  // var sar=$('.fxxkU').index();
                  // if(sar !='-1'){
                  //     $.get(url + num,'', function(data){
                  //         if(data != "0"){
                  //             $('#applied_at').removeAttr("onclick").val("");
                  //             layer.alert('该申报批次已存在，请重新输入');
                  //             return false;
                  //         }else {
                  //             $('#applied_at').attr("onclick",'laydate()');
                  //         }
                  //     })
                  // }
              },
              end: function () {
              },
              complete:function(){
                  var scrape = memoize(function(url) {
                      return $.get('/admin/scraper', { 'url': url })
                  })
              }
          });
          ele = $('#layui-layer' + index + ' [data-gctable]');
          tableShow(ele);

      },
      error:function(){
          ajaxerr(XMLHttpRequest);
      }
  })

  // $.get(api, {}, function (str) {
  //     var index = layer.open({
  //     type: 1,
  //     title: outerTitle,
  //     area: outerWidth,
  //     offset: '100px',
  //     btn: ['确定'],
  //     content: str,
  //     yes: function (i) {
  //         ele.find('input:checked').closest('tr').each(function () {
  //         pclass == '.ptbody' && ptbody.append(addTr(this.dataset.id));
  //         pclass == '.otbody' && ptbody.append(addOTr(this.dataset.id));
  //         pclass == '.ftbody' && ptbody.append(addFTr(this.dataset.id));
  //         pclass == '.pertbody' && ptbody.append(addPETr(this.dataset.id));
  //         pclass == '.roltbody' && ptbody.append(addPETr(this.dataset.id));
  //       });
  //       layer.close(i);
  //       //申报登记
  //       var url ="/filing/number/";
  //       var num = $('.form-control.fxxkU').val();
  //       var sar=$('.fxxkU').index();
  //       if(sar !='-1'){
  //         $.get(url + num,'', function(data){
  //           if(data != "0"){
  //             $('#applied_at').removeAttr("onclick").val("");
  //             layer.alert('该申报批次已存在，请重新输入');
  //             return false;
  //           }else {
  //             $('#applied_at').attr("onclick",'laydate()');
  //           }
  //         })
  //       }
  //     },
  //     end: function () {
  //
  //     }
  //   });
  //   ele = $('#layui-layer' + index + ' [data-gctable]');
  //   tableShow(ele);
  // }).error(function(XMLHttpRequest, textStatus, errorThrown,data) {
  //     ajaxerr(XMLHttpRequest);
  // });

});

//查看产品详情(订单管理)
$(document).on('click', '[data-order]', function () {
  var api = '/admin/' + $(this).data().order + '/read/' + $(this).data().id,
    outerWidth = $(this).data().outwidth,
    outerTitle = '产品详情：' + $(this).html();
  $.get(api, {}, function (str) {
    layer.open({
      type: 1,
      title: outerTitle,
      area: outerWidth,
      offset: '100px',
      content: str
    });
  }).error(function(XMLHttpRequest, textStatus, errorThrown,data) {
      ajaxerr(XMLHttpRequest);
  });
});
// order 出货的产品 单位的弹窗选择
//查看产品详情(订单管理)

  $(document).on('focus', '[data-units]', function () {
      reminder($(this));
  });
  var alldata=[];
  //  input匹配
function reminder($this){
    // 获取当前对象的一些样式
    //  input 框
    var input = $this;
    //  input 框的下拉选择列表
    if(!input.next().hasClass('search_suggest')){
        input.parent().css('position','relative').append("<div class='search_suggest' ><ul></ul> </div> ");
        // 接口api
        var Api=input.data('api');
        $.get(Api,{},function(data){
            alldata =data;
        });
    }
    // 宽高
    input.next().css({
        top:input.parent().height(),
        width:input.parent().width()
    });
    //定义当前下拉选择列表
    var suggestWrap =input.next('.search_suggest');
    function oSearchSuggest(searchFuc){
        var key = "";
        var init = function(){
            input.bind('keyup',sendKeyWord);
            input.parent().bind('mouseleave',function(){setTimeout(hideSuggest,100);})

        };
        var hideSuggest = function(){
            suggestWrap.hide();
        };

        //发送请求，根据关键字到后台查询
        var sendKeyWord = function(event){

            //键盘选择下拉项
            if(suggestWrap.css('display')=='block'&&event.keyCode == 38||event.keyCode == 40){
                var current = suggestWrap.find('li.hover');
                if(event.keyCode == 38){
                    if(current.length>0){
                        var prevLi = current.removeClass('hover').prev();
                        if(prevLi.length>0){
                            prevLi.addClass('hover');
                            input.val(prevLi.html());
                        }
                    }else{
                        var last = suggestWrap.find('li:last');
                        last.addClass('hover');
                        input.val(last.html());
                    }

                }else if(event.keyCode == 40){
                    if(current.length>0){
                        var nextLi = current.removeClass('hover').next();
                        if(nextLi.length>0){
                            nextLi.addClass('hover');
                            input.val(nextLi.html());
                        }
                    }else{
                        var first = suggestWrap.find('li:first');
                        first.addClass('hover');
                        input.val(first.html());
                    }
                }

                //输入字符
            }else{
                var valText = $.trim(input.val());
                if(valText ==''||valText==key){
                    return;
                }
                searchFuc(valText);
                key = valText;
            }

        }
        //请求返回后，执行数据展示
        this.dataDisplay = function(data){
            if(data.length<=0){
                suggestWrap.hide();
                return;
            }

            //往搜索框下拉建议显示栏中添加条目并显示
            var tmpFrag = '';
            suggestWrap.find('ul').html('');
            for(var i=0; i<data.length; i++){

                tmpFrag+="<li class='Li_data'>"+data[i]+"</li> ";

            }
            suggestWrap.find('ul').append(tmpFrag);
            suggestWrap.show();

            //为下拉选项绑定鼠标事件
            suggestWrap.find('li').hover(function(){
                suggestWrap.find('li').removeClass('hover');
                $(this).addClass('hover');

            },function(){
                $(this).removeClass('hover');
            }).bind('click',function(){
                input.val(this.innerHTML);
                suggestWrap.hide();
            })
        }
        init();
    };

    //实例化输入提示的JS,参数为进行查询操作时要调用的函数名
    var searchSuggest =  new oSearchSuggest(sendKeyWordToBack);
    //参数为一个字符串，是搜索输入框中当前的内容
    function sendKeyWordToBack(keyword){
        // 根据接口获取当前全部的数据，然后做匹配。
        var aData = [];
        $.each(alldata,function(i,n){
            var len = keyword.length;
            for(var i=0;i<len;i++){
                if(n.indexOf(keyword)>=0){
                    aData.push(n);
                }
            }
        });
        searchSuggest.dataDisplay(aData);
    }
}

//将所选产品渲染到页面（开票人管理）
// function addTr(v) {
//  $('.zwsj').attr('disabled','disabled');
//   var html = '';
//   console.log(window['proList']);
//   $.each(window['proList'], function (a, b) {
//     if (v == b.id) {
//       html += '<tr data-id="' + b.id + '"> ' +
//         '<td><img src="' + b.picture.split("|")[0] + '" onclick="imgMagnify($(this).attr(\'src\'))"></td> ' +
//         '<td> ' +
//         '<input type="text" value="' + b.name + '" readonly> ' +
//         '</td> ' +
//         '<td> ' +
//         '<input type="text" value="' + b.hscode + '" readonly> ' +
//         '</td> ' +
//         '<td > ' +
//           '<input type="text" value="' + b.standard + '" readonly> ' +
//           '</td> ' +
//           '<td > ' +
//           '<input type="text" value="' + b.number + '" readonly> ' +
//           '</td> ' +
//         '<td class="text-center text-danger pbtn deleteBox">删除</td>' +
//         '</tr>'
//     }
//   });
//   return html;
// }

//将所选产品渲染到页面（订单管理）
// function addOTr(v) {
//   //  data-order="product"  弹出窗详情
//   //  data-units 单位
//   var html = '';
//   $.each(window['proList'], function (a, b) {
//     if (v == b.id) {
//       html += '<tr data-id="' + b.id + '">' +
//         '<input type="hidden" name="pro[' + indexTr + '][drawer_product_id]" value="' + b.id + '">' +
//         '<td data-id="' + b.product_id + '" data-order="product" data-outwidth="900px">' + b.product.name + '</td>' +
//         '<td data-id="' + b.product_id + '" data-order="product" data-outwidth="900px">' + b.drawer.company + '</td>' +
//         '<td>' +
//         '<input type="text" data-parsley-required  data-parsley-trigger="change" name="pro[' + indexTr + '][number]" class="number text-center">' +
//         '</td>' +
//         '<td>' +
//         '<input type="text" data-parsley-required  data-units="unit" autocomplete="off" data-api="/admin/data/father/15" data-id="' + b.product_id + '" data-parsley-trigger="change" name="pro[' + indexTr + '][unit]" value="'+ b.product.unit +'" class="unit text-center">' +
//         '</td>' +
//         '<td>' +
//         '<input readonly type="text" data-parsley-type="number"  name="pro[' + indexTr + '][single_price]" class="single_price text-center">' +
//         '</td>' +
//         '<td>' +
//         '<input type="text"  data-parsley-required data-parsley-type="number" data-parsley-type="number" data-parsley-trigger="change" name="pro[' + indexTr + '][total_price]"  class="total_price  text-center">' +
//         '</td>' +
//         '<td>' +
//         '<input type="text" data-parsley-required  data-parsley-trigger="change" name="pro[' + indexTr + '][default_num]" class="default_number text-center">' +
//         '</td>' +
//         '<td>' +
//         '<input type="text" data-parsley-required data-parsley-type="number" data-parsley-trigger="change" name="pro[' + indexTr + '][value]" class="text-center default_price">' +
//         '</td>' +
//         '<td>' +
//         '<input type="text" data-parsley-required data-parsley-type="number" data-parsley-trigger="change" name="pro[' + indexTr + '][total_weight]" class="total_weight text-center">' +
//         '</td>' +
//         '<td>' +
//         '<input type="text" data-parsley-required data-parsley-type="number" data-parsley-trigger="change" name="pro[' + indexTr + '][net_weight]" class="net_weight text-center">' +
//         '</td>' +
//         '<td>' +
//         '<input type="text" data-parsley-required data-parsley-type="number" data-parsley-trigger="change" name="pro[' + indexTr + '][volume]" class="text-center volume">' +
//         '</td>' +
//         '<td>' +
//         '<input type="text" data-parsley-required data-parsley-type="number" data-parsley-trigger="change" name="pro[' + indexTr + '][pack_number]" class="text-center pack_number">' +
//         '<input type="hidden" name="pro[' + indexTr + '][tax_refund_rate]" value="' + b.product.tax_refund_rate + '" class="text-center tax_refund_rate"/>'+
//         '</td>' +
//         //'<td>' +
//         //'<input type="text" id="payNum" data-before="customer_id" placeholder="请选择定金单号" readonly   name="pro['+ indexTr +'][pay_number]" data-followed="payment"   data-target="[name=pay_id]" class="text-center pay_number" >'+
//         //'<td style="display:none">' +
//         //'<input type="hidden"  readonly  data-parsley-type="number" value=""  name="pro[' + indexTr + '][pay_id]"  class="text-center pay-id" >'+
//         //'</td>' +
//         //'<td>' +
//         //'<input type="text" data-parsley-trigger="change" data-parsley-required  id="buyerId"  placeholder="请选择采购员" name="pro['+ indexTr +'][buyer]" data-choose="choosebuyer"  data-target="[name=u_name]" class="text-center buyer" >'+
//         //'<input type="hidden" name="pro[' + indexTr + '][u_name]" value="" class="text-center u_name"/>'+
//         //'</td>' +
//         '<td>'+
//         '<input type="text" data-parsley-required data-parsley-type="number" data-parsley-trigger="change" name="pro[' + indexTr + '][merge]" class="text-center merge">' +
//         '</td>'+
//         '<td class="text-center text-danger deleteBox">删除</td>' +
//         '</tr>';
//       indexTr++;
//     }
//   });
//   return html;
// }

//将所选发票渲染到页面（申报登记）
// function addFTr(v) {
//   var html = '';
//   $.each(window['invList'], function (a, b) {
//     if (v == b.id) {
//       html += '<tr data-id="' + b.id + '">' +
//         '<td>' + b.order.ordnumber + '</td>' +
//         '<td>' + b.number + '</td>' +
//         '<td>' + b.billed_at + '</td>' +
//         '<td>' + b.invoice_amount + '</td>' +
//         '<td>' + b.received_at + '</td>' +
//         '</tr>';
//     }
//   });
//   return html;
// }

//选择权限、角色（系统管理）
function addPETr(v) {
  var html = '';
  $.each(window['perList'], function (a, b) {
    if (v == b.id) {
      html += '<tr data-id="' + b.id + '">' +
        '<td>' + b.name + '</td>' +
        '<td>' + b.description + '</td>' +
        '<td class="text-center text-danger deleteBox">删除</td>' +
        '</tr>';
    }
  });
  return html;
}
//根据所选开票工厂，将关联信息渲染到页面(退税结算)
// function addSTr(v){
//    //结算页面,付款信息
//    var html = '';
//    indexTr = $('.stbody>tr').length;
//    $.each(window['comList'],function(a,b){
//        for(var i=0;i<$('.stbody tr').length;i++){
//            if($('.stbody tr')[i].dataset.id == b.id){
//              layer.msg('该开票工厂已使用,请重新选择');
//              return false;
//            }
//        }
//        if(v == b.id){
//            html += '<tr data-id="' + b.id + '">' +
//            '<td>' + $('.stbody').children('tr').length + 1 + '</td>' +
//            '<input type="hidden" name="pro[' + indexTr + '][settlement_id]" value="'+$('.settlement_id').val()+'">' +
//            '<input type="hidden" name="pro['+ indexTr +'][drawer_id]" value="'+ b.id +'">' +
//            '<td class="settle_company">' +
//            '<input type="text" value="'+b.company+'" readonly data-parsley-type="integer" data-parsley-trigger="change" name="pro[' + indexTr + '][company]" class="total_amount form-control" readonly>'+
//            '</td>' +
//            '<td>' +
//            '<input type="text" value="0" data-parsley-type="number" data-parsley-trigger="change" name="pro[' + indexTr + '][invoice_value]" class="invoice_value form-control" readonly>' +
//            '</td>' +
//            '<td>'+
//            '<input type="text" value="0" data-parsley-type="number" data-parsley-trigger="change" name="pro[' + indexTr + '][invoice_sum]" class="invoice_sum form-control" readonly>' +
//            '</td>' +
//            '<td>' +
//            '<input type="text" value="0" data-parsley-type="number" data-parsley-trigger="change" name="pro[' + indexTr + '][paid_deposit]" class="paid_deposit form-control" readonly>' +
//            '</td>' +
//            '<td>' +
//            '<input type="text" value="0" data-parsley-type="number" name="pro[' + indexTr + '][proposed_paid]"  class="proposed_paid form-control" readonly>' +
//            '</td>' +
//            '<td>' +
//            '<input type="text" value="0" data-parsley-type="number" name="pro[' + indexTr + '][proposed_deposit]"  class="proposed_deposit form-control">' +
//            '</td>' +
//            '</tr>';
//        }
//    })
//    return html;
// }


//根据所选订单，将关联产品渲染到页面（发票管理）
// function addPTr(v) {
//   var html = '', money = 0;
//   var drawer_id =  $("input[name='drawer_id']").val();
//   $.each(v.order.drawer_products, function (a, b) {
//       var   $th_p=(b.pivot.number - (typeof (v.product_count[b.id]) == 'undefined' ? 0 : v.product_count[b.id].number))
//     if($th_p>0 && drawer_id == b.drawer_id){
//     html += '<tr>' +
//       '<input type="hidden" name="inv[' + b.id + '][drawer_product_id]" value="' + b.id + '">' +
//       '<input type="hidden" name="inv[' + b.id + '][clearance_id]" value="' + v.id + '">' +
//       '<td class="jincieryi">' + (a + 1) + '</td>' +
//       '<td>' + b.product.hscode + '</td>' +
//       '<td>' + b.product.name + '</td>' +
//       '<td>' + b.pivot.unit + '</td>' +
//       '<td>' + b.pivot.number + '</td>' +
//       '<td>' +
//        '<select class="form-control gog-select text-center huiv rate" name="inv[' + b.id + '][rate]" data-parsley-required data-parsley-trigger="change" ></select>'+
//           '</td>' +
//       '<td class="untax_price"></td>' +
//       '<td class="untax_amount"></td>' +
//       '<td>' +
//       '<input type="text" name="inv[' + b.id + '][number]" data-parsley-type="integer" data-parsley-required data-parsley-trigger="change" placeholder="请填写数量" class="number text-center">' +
//       '</td>' +
//       '<td>' +
//       '<input type="text" name="inv[' + b.id + '][amount]" data-parsley-type="number" data-parsley-required data-parsley-trigger="change" placeholder="请填写发票金额" class="price text-center">'+
//       '</td>' +
//       '<td class="rest_amount" data-value="' + (b.pivot.number - (typeof (v.product_count[b.id]) == 'undefined' ? 0 : v.product_count[b.id].number)) + '">' + (b.pivot.number - (typeof (v.product_count[b.id]) == 'undefined' ? 0 : v.product_count[b.id].amount)) + '</td>' +
//       '<td class="gather_price" data-value="' + (typeof (v.product_count[b.id]) == 'undefined' ? 0 : v.product_count[b.id].sum) + '">' + (typeof (v.product_count[b.id]) == 'undefined' ? 0 : v.product_count[b.id].sum) + '</td>' +
//       '<td class="text-center text-danger deleteBox">删除</td>' +
//       '</tr>';
//     }
//   });
//   return html;
// }

//添加产品为必填(开票人管理&&订单管理(货柜、产品)&&申报管理)
function addPro(pid) {
  if ($('.ptbody').length != 0) {
    if ($('.ptbody>tr').length == 0) {
      layer.msg('请添加开票人产品！');
      return false;
    } else {
      $('.ptbody>tr').each(function () {
        pid.push(this.dataset.id);
      });
      $('[name=pid]').val(pid);
    }
    return true;
  } else if ($('.ftbody').length != 0) {
    if ($('.ftbody>tr').length == 0) {
      layer.msg('请添加发票！');
      return false;
    } else {
      $('.ftbody>tr').each(function () {
        pid.push(this.dataset.id);
      });
      $('[name=invoices_id]').val(pid);
    }
    return true;
  } else if ($('.otbody').length != 0) {
    if ($('.otbody>tr').length == 0) {
      layer.msg('请添加需出货产品！');
      return false;
    }
    return true;
  } else if ($('.pertbody').length != 0) {
    if ($('.pertbody>tr').length == 0) {
      layer.msg('请分配权限！');
      return false;
    } else {
      $('.pertbody>tr').each(function () {
        pid.push(this.dataset.id);
      });
      $('[name=permission_id]').val(pid);
    }
    return true;
  }  else if ($('.roltbody').length != 0) {
    if ($('.roltbody>tr').length == 0) {
      layer.msg('请选择角色！');
      return false;
    } else {
      $('.roltbody>tr').each(function () {
        pid.push(this.dataset.id);
      });
      $('[name=role_id]').val(pid);
    }
    return true;
  } else {
    return true;
  }
}

//js 乘法函数 返回值：arg1乘以arg2的精确结果
function accMul(arg1, arg2) {
  var m = 0, s1 = arg1.toString(), s2 = arg2.toString();
  try {
    m += s1.split(".")[1].length
  } catch (e) {
  }
  try {
    m += s2.split(".")[1].length
  } catch (e) {
  }
  return Number(s1.replace(".", "")) * Number(s2.replace(".", "")) / Math.pow(10, m)
}

//js 除法函数  返回值：arg1除以arg2的精确结果
function accDiv(arg1, arg2, n) {
  var t1 = 0, t2 = 0, r1, r2;
  try {
    t1 = arg1.toString().split(".")[1].length
  } catch (e) {
  }
  try {
    t2 = arg2.toString().split(".")[1].length
  } catch (e) {
  }
  with (Math) {
    r1 = Number(arg1.toString().replace(".", ""));
    r2 = Number(arg2.toString().replace(".", ""));
    return ((r1 / r2) * pow(10, t2 - t1)).toFixed(n);
  }
}

//js 加法计算  返回值：arg1加arg2的精确结果
function accAdd(arg1, arg2, n) {
  var r1, r2, m;
  try {
    r1 = arg1.toString().split(".")[1].length
  } catch (e) {
    r1 = 0
  }
  try {
    r2 = arg2.toString().split(".")[1].length
  } catch (e) {
    r2 = 0
  }
  m = Math.pow(10, Math.max(r1, r2));
  return ((arg1 * m + arg2 * m) / m).toFixed(n);
}

//js 减法计算  返回值：arg1减arg2的精确结果
function accSub(arg1, arg2, a) {
  var r1, r2, m, n;
  try {
    r1 = arg1.toString().split(".")[1].length
  } catch (e) {
    r1 = 0
  }
  try {
    r2 = arg2.toString().split(".")[1].length
  } catch (e) {
    r2 = 0
  }
  m = Math.pow(10, Math.max(r1, r2));
  //动态控制精度长度
  n = (r1 >= r2) ? r1 : r2;
  return ((arg1 * m - arg2 * m) / m).toFixed(a);
}
//  自定义验证

window.Parsley.addValidator('zw', {
    validateString: function(value) {
        var zw = /[\u4e00-\u9fa5]+/g;
        if (zw.test(value)){//
//开始校验吧
            return false;//校验不通过,会将data-parsley-zw-message对应的信息输出

        }
    }
});
// 两位小数点验证
window.Parsley.addValidator('number', {
    validateString: function(value) {
        var number = /^\d+(\.\d{1,2})?$/;
        if (!number.test(value)){//
            return false;//校验不通过,会将data-parsley-number-message对应的信息输出
        }
        return true;//检验通过
    }
});

// isEmail验证
window.Parsley.addValidator('isEmail', {
    validateString: function(value) {
        var number = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
        if (!number.test(value)){//
            return false;//校验不通过,会将data-parsley-number-message对应的信息输出
        }
        return true;//检验通过
    }
});
//自定义序列化功能
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
