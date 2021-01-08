function ObjEach(obj, e) {
  var html = ''
  for (var i in obj) {
    html += '<option value="' + i + '">' + obj[i] + '</option>'
  }
  e.html(html)
}

//获取开票人列表
function getDrawers(page) {
  $.get('/api/drawer', {
    token: $.cookie("token"),
    pageSize: 10,
    status: 3
  }, function (res) {
    data = res.data.data
    var html = ''
    for (var i = 0; i < data.length; i++) {
      html += '<tr data-id="' + data[i].id + '">' +
        '<td>' +
        '<small>' + data[i].company + '</small>' +
        '</td>' +
        '<td>' +
        '<small>' + data[i].tax_id + '</small>' +
        '</td>' +
        '<td>' +
        '<small>' + (data[i].created_at !== null ? data[i].created_at : '') + '</small>' +
        '</td>' +
        '<td>' +
        '<small>' + data[i].status_str + '</small>' +
        '</td>' +
        '</tr>'
    }
    $('#drawer_table tbody').html(html)
    crepage($('#page'), res.data.currentPage, res.data.total, getDrawers)
  })
}

//货物存放地
function getDeposit() {
  $.get('/api/deposit', {
    token: $.cookie("token"),
    pageSize: 10,
    status: 3
  }, function (res) {
    var html = '',
      data = res.data.data;
    for (var i = 0; i < data.length; i++) {
      html += '<tr id="' + data[i].id + '" >' +
        '<td><input name="deposit" type="radio" value="' + data[i].id + '" rel="' + data[i].name +
        '" address="' + data[i].address + '"></td>' +
        '<td>' + data[i].address + '</td>' +
        '<td>' + data[i].name + '</td>' +
        '<td class="address_input">' + data[i].phone + '</td>' +
        '<td></td>' +
        '</tr>'
    }
    html2 = `<tr>
          <td>
            <input type="radio" name="deposit">
          </td>
          <td>
            <input type="text" name="address" class="am-form-field" placeholder="详细地址">
          </td>
          <td>
            <input type="text" name="name" class="am-form-field" placeholder="姓名">
          </td>
          <td>
            <input type="text" name="phone" class="am-form-field" placeholder="联系人电话">
          </td>
          <td>
            <a type="button" class="deposit_btn am-btn am-btn-default">保存</a>
          </td>
      </tr>`
    $('#depositList tbody').html(html + html2)
  })
}

function sum(num, aim, dot) {
  var total = 0
  $('.pro_sec input[name="' + num + '"]').map(function (index, item) {
    item.value == '' ? (a = 0) : (a = item.value * 1)
    total = total + a
  })
  total = total.toFixed(dot)
  $(aim).val(total)
}


//联系人
function getLinks() {
  $.get('/api/links', {
    token: $.cookie("token"),
    pageSize: 10,
    status: 3
  }, function (res) {
    var html = '',
      html2 = '',
      data = res.data.data;
    for (var i = 0; i < data.length; i++) {
      html += '<tr data-id="' + data[i].id + '">' +
        '<td><input type="radio" name="linksId" value="' + data[i].id + '" rel="' + data[i].name + '"></td>' +
        '<td><input  class="am-form-field" type="text" readonly value="' + data[i].name + '"></td>' +
        '<td class="phone_input"><input  class="am-form-field" type="text" readonly value="' + data[i].phone +
        '"></td>' +
        '<td><a type="button" data-id="' + data[i].id +
        '" data-type="1" class="links_update am-btn am-btn-default">修改</a></td>' +
        '</tr>'
    }
    html2 = '<tr>' +
      '<td>' +
      '<input type="radio" name="linksId">' +
      '</td>' +
      '<td>' +
      '<input type="text" name="name" class="am-form-field" placeholder="姓名" value="">' +
      '</td>' +
      '<td>' +
      '<input type="text" name="phone" class="am-form-field" placeholder="联系人电话"  value="">' +
      '</td>' +
      '<td>' +
      '<a type="button" class="links_btn am-btn am-btn-default">保存</a>' +
      '</td>' +
      '</tr>'
    $('#linksList tbody').html(html + html2)
  })
}

//增加产品的产品列表
function getProList(page) {
  $.get('/api/drawer_products', {
    token: $.cookie("token"),
    pageSize: 10,
    status: 3
  }, function (res) {
    var html = '',
      html2 = '',
      data = res.data.data;
    for (var i = 0; i < data.length; i++) {
      html +=`<tr data-id="${data[i].drawer_product_id}" rel="${data[i].product_id}">
        <td>
          <img src="${data[i].picture}" alt="" width="50">
        </td>
        <td>
            <p>${data[i].name}</p>
        </td>
        <td>
          <p>${data[i].standard}</p>
        </td>
        <td>
          <p>${data[i].hscode}</p>
        </td>
      </tr>`
    }
    $('#product_list tbody').html(html)
    crepage($('#pro_page'), res.data.currentPage, res.data.total, getProList)
  })

}

//收货人
function getReceive() {
  $.get('/api/receive', {
    token: $.cookie("token")
  }, function (res) {
    var html = '',
    data = res.data.data;
    for (var i = 0; i < data.length; i++) {
      html += '<tr id="' + data[i].id + '">' +
        '<td><input type="radio" value="' + data[i].id + '" rel="' + data[i].name + '"></td>' +
        '<td>' + data[i].name + '</td>' +
        '<td class="country_td">' + data[i].country + '</td>' +
        '<td>' + data[i].address + '</td>' +
        '<td>' + data[i].aeocode + '</td>' +
        '<td>' + data[i].fax + '</td>' +
        '<td>' + data[i].web + '</td>' +
        '<td class="phone_input">' + data[i].phone + '</td>' +
        '<td></td>' +
        '</tr>'
    }
    html2 = '<tr>' +
      '<td>' +
      '<input type="radio" name="cdainfo">' +
      '</td>' +
      '<td>' +
      '<input type="text" name="name" class="am-form-field" placeholder="名称">' +
      '</td>' +
      '<td>' +
      '<input type="text" name="country" class="am-form-field" placeholder="国家">' +
      '</td>' +
      '<td>' +
      '<input type="text" name="address" class="am-form-field" placeholder="详细地址">' +
      '</td>' +
      '<td>' +
      '<input type="text" name="aeocode" class="am-form-field" placeholder="AEO编码">' +
      '</td>' +
      '<td>' +
      '<input type="text" name="fax" class="am-form-field" placeholder="传真">' +
      '</td>' +
      '<td>' +
      '<input type="text" name="web" class="am-form-field" placeholder="网址">' +
      '</td>' +
      '<td>' +
      '<input type="text" name="phone" class="am-form-field" placeholder="收件人联系电话">' +
      '</td>' +
      '<td>' +
      '<a type="button" class="receive_btn am-btn am-btn-success">保存</a>' +
      '</td>' +
      '</tr>'
    $('#receiveList tbody').html(html + html2)
  })
}
$(function () {
  //获取下拉框的选项
  $.get('/api/order/create', {
    token: $.cookie('token')
  }, function (res) {
    var data = res.data
    // ObjEach(data.clearance_port, $('#clearance_port'))
    ObjEach(data.declare_mode, $('#declare_mode'))
    ObjEach(data.currency, $('#currency'))
    ObjEach(data.transport, $('#transport'))
    ObjEach(data.order_package, $('#order_package'))
    ObjEach(data.package, $('#package'))
    ObjEach(data.price_clause, $('#price_clause'))
    ObjEach(data.sales_unit, $('#sales_unit'))
    ObjEach(data.shipment_port, $('#shipment_port'))
    ObjEach(data.loading_mode, $('#loading_mode'))
  }).then(function () {
    //编辑时赋值
    if (getUrlParam("id") != null && getUrlParam("id") != '') {
      $.get('/api/order/' + getUrlParam('id'), {
        token: $.cookie('token')
      }, function (res) {
        var data = res
        var json = data.data
        $.get('/api/port', {
          token: $.cookie('token')
        }, function (res) {
          if (res.code == 200) {
            $('#trade_country_name').val(json.tradecountry.country_na)
            $('#aim_country_name').val(json.country.country_na)
            $('#unloading_port_name').val(json.unloadingport.port_c_cod)
          } else {
            layer.msg(res.code + res.msg)
          }
        })
        //循环遍历赋值
        for (x in json) {
          if ($('select#' + x).length > 0) {
            $('#' + x).val(json[x]).change()
          } else {
            $("#" + x).val(json[x])
          }
        }
        $('#links').val(json.link_id)
        $('#receive').val(json.receive_id)
        $('#deposit').val(json.deposit_id)
        $('[name="attach_upload"]').val(json.attach_upload)
        // setTimeout(function () {
        //   $('#unloading_port').val(json.unloading_port)
        // }, 600)
        $("[name='exchange_method'][value='" + json.exchange_method + "']").attr("checked", true)
        $("[name='enter_price_method'][value='" + json.enter_price_method + "']").attr("checked", true)
        $("[name='is_special'][value='" + json.is_special + "']").attr("checked", true)
        $("[name='is_pay_special'][value='" + json.is_pay_special + "']").attr("checked", true)
        var html = ''
        //联系人
        if (json.link != null && json.link != '') {
          $("#liken").html('<p class="am-radio"><input type="radio" checked="checked"><small>' + json.link
            .name + '</small><small style="margin-left:10px;">' + json.link.phone +
            '</small></p>')
        }
        //货物存放地
        if (json.deposit != null && json.deposit != '') {
          $("#Storage_address").html(
            '<p class="am-radio"><input type="radio" checked="checked"><small class="">' + json.deposit
            .name +
            '</small><small name="storage_phone" style="margin-left:10px;">' + json.deposit.address +
            '</small></p>')
        }
        //收货人
        if (json.receive != null && json.receive != '') {
          $("#receivep").html('<p class="am-radio"><input type="radio" checked="checked"><small>' +
            json.receive
            .name + '</small><small style="margin-left:10px;">' + json.receive.country +
            '</small></p>')
        }
        //产品的数组
        var jsondrawer_products = json.drawer_products
        for (var i = 0; i < jsondrawer_products.length; i++) {
          for (var index in jsondrawer_products[i].product) {
            jsondrawer_products[i].product[index] == null || jsondrawer_products[i].product[index] == undefined ?
              jsondrawer_products[i].product[index] = '' : ''
          }
          html += `<section class="am-panel am-panel-default">
              <header class="am-panel-hd">
                <h5 class="am-panel-title">产品名称：
                    <input disabled="disabled" id="name" style="width:50%" value="${jsondrawer_products[i].product.name}(${jsondrawer_products[i].pivot.company})">
                    <input type="hidden" id="company" name="company" value="` +jsondrawer_products[i].pivot.company +`">
                </h5>
              </header>
              <div class="pro_sec">
                <ul class="clearfix" style="min-width: 1000px;">
                  <li>
                    <div>
                      <label>产品分类：</label>
                      <input name="drawer_product_id" type="hidden" id="id" value="` +
            jsondrawer_products[i].pivot.drawer_product_id +
            `">
                      <input id="brand_type" value="` +
            (jsondrawer_products[i].product.brand_id == 0 ? '':jsondrawer_products[i].product.brand.classify_str)+
            `" disabled="disabled">
                    </div>
                  </li>
                  <li>
                    <div>
                      <label>品牌类型：</label>
                      <input value='` +
            (jsondrawer_products[i].product.brand_id == 0 ? '':jsondrawer_products[i].product.brand.type_str) +
            `' disabled="disabled">
                    </div>
                  </li>
                  <li class="last_li">
                    <div>
                      <label><small class="red">*</small>英文品名：</label>
                      <input id="en_name"  data-type="required" value='` +
            jsondrawer_products[i].product.en_name +
            `' disabled="disabled">
                    </div>
                  </li>
                  <li>
                    <div>
                      <label><small class="red">*</small>境内货源地：</label>
                      <input placeholder="请选择境内货源地"  data-type="required" name="domestic_source" class="domestic_source_con" id="domestic_source"
                       value='` + jsondrawer_products[i].pivot.domestic_source +`' style="width:70%" readonly />
                       <input type="hidden" name="domestic_source_id" value="${jsondrawer_products[i].pivot.domestic_source_id}">
                    </div>
                  </li>
                  <li>
                    <div>
                      <label>产地：</label>
                      <input placeholder="请填写产地" name="production_place" id="production_place" value='` +
            (jsondrawer_products[i].pivot.production_place == null ? '':jsondrawer_products[i].pivot.production_place) +`'>
                    </div>
                  </li>
                  <li  class="last_li">
                    <div>
                      <label><small class="red">*</small>原产国(地区)：</label>
                      <input placeholder="请填写原产国" class="country_openCon" data-type="required" name="origin_country" id="origin_country"
                        value='${jsondrawer_products[i].pivot.origin_country}' readonly />
                      <input type="hidden" name="origin_country_id" value="${jsondrawer_products[i].pivot.origin_country_id}">
                    </div>
                  </li>
                  <li >
                    <div>
                      <label><small class="red">*</small>最终目的国(地区)： </label>
                      <input placeholder="请填写最终目的国" class="country_openCon" data-type="required" name="destination_country" id="destination_country"
                        value='${jsondrawer_products[i].pivot.destination_country}' readonly />
                      <input type="hidden" name="destination_country_id" value="${jsondrawer_products[i].pivot.destination_country_id}">
                    </div>
                  </li>
                  <li>
                    <div>
                      <label><small class="red">*</small>货物属性：</label>
                      <select class="removeClass" name="goods_attribute"  data-type="required"  id="goods_attribute" value='` +
            jsondrawer_products[i].pivot.goods_attribute +
            `'>
                        <option value="0">自产</option>
                        <option value="1">委托加工</option>
                        <option value="2">外购</option>
                      </select>
                    </div>
                  </li>
                  <li  class="last_li">
                    <div>
                      <label><small class="red">*</small>HSCode：</label>
                      <input placeholder="请填写HSCode"  data-type="required" id="hscode" value='` +
            jsondrawer_products[i].product.hscode +
            `' disabled="disabled">
                    </div>
                  </li>
                  <li>
                    <div>
                      <label>CIQ编码：</label>
                      <input name="ciq_code" id="ciq_code" value='` +
            (jsondrawer_products[i].pivot.ciq_code == null ? '':jsondrawer_products[i].pivot.ciq_code) +
            `'>
                    </div>
                  </li>
                  <li>
                    <div>
                      <label>退税率：</label>
                        <input name="tax_refund_rate" id="tax_refund_rate" value="` +
            jsondrawer_products[i].pivot.tax_refund_rate +
            `" disabled="disabled">
                    </div>
                  </li>
                  <li class="last_li">
                    <div>
                      <label>税率</label>
                      <input name="tax_rate" id="tax_rate" value='` +
            jsondrawer_products[i].pivot.tax_rate +
            `'>
                    </div>
                  </li>
                  <li class="">
                    <div>
                      <label>种类：</label>
                      <input placeholder="请填写种类" name="species" id="species" value='` +
            (jsondrawer_products[i].pivot.species == null ? '':jsondrawer_products[i].pivot.species) +
            `'>
                    </div>
                  </li>
                  <li>
                    <div>
                      <label>表面材质：</label>
                      <input placeholder="请填写表面材质" name="surface_material" id="surface_material" value='` +
            (jsondrawer_products[i].pivot.surface_material == null ? '':jsondrawer_products[i].pivot.surface_material) +`'>
                    </div>
                  </li>
                  <li  class="last_li">
                    <div>
                      <label>款号：</label>
                      <input placeholder="请填写款号" name="section_number" id="section_number" value='` +
            (jsondrawer_products[i].pivot.section_number == null ? '':jsondrawer_products[i].pivot.section_number) +`'>
                    </div>
                  </li>
                  <li  class="last_li" style="width:100%">
                    <div>
                      <label>出口货物在最终目的国（地区）是否享受优惠关税：</label>
                      <select class="removeClass" name="enjoy_offer" id="enjoy_offer" value='` +
            jsondrawer_products[i].pivot.enjoy_offer +`'>
                        <option>-- 请选择 --</option>
                        <option value="0">享受</option>
                        <option value="1">不享受</option>
                        <option value="2">不能确定</option>
                      </select>
                    </div>
                  </li>
                  <li>
                    <div>
                      <label><small class="red">*</small>最大包装件数：</label>
                      <input placeholder="请填写最大包装件数"  data-type="required" name="pack_number" id="pack_number" value='` +
            jsondrawer_products[i].pivot.pack_number +`'>
                    </div>
                  </li>
                  <li>
                    <div>
                      <label> <small class="red">*</small>净重(KG)：</label>
                      <input placeholder="请填写净重(KG)" data-type="required" name="net_weight" id="net_weight" value='` +
            jsondrawer_products[i].pivot.net_weight + `'>
                    </div>
                  </li>
                  <li class="last_li">
                    <div>
                      <label> <small class="red">*</small>毛重(KG)：</label>
                      <input placeholder="请填写毛重(KG)" data-type="required" name="total_weight" id="total_weight"
                      value='` + jsondrawer_products[i].pivot.total_weight + `'>
                    </div>
                  </li>
                  <li>
                    <div>
                      <label> <small class="red">*</small>体积(CBM)：</label>
                      <input placeholder="请填写产品体积(CBM)" data-type="required" name="volume" id="volume"
                      value='` + jsondrawer_products[i].pivot.volume + `'>
                    </div>
                  </li>
                  <li>
                    <div>
                      <label> <small class="red">*</small>产品数量：</label>
                      <input placeholder="请填写产品数量" data-type="required" name="number" id="number"
                      value='` + jsondrawer_products[i].pivot.number + `'>
                    </div>
                  </li>

                  <li>
                    <div>
                      <label>单价(USD)：</label>
                      <input placeholder="请填写单价(USD)" name="single_price" id="single_price"
                      value='` + jsondrawer_products[i].pivot.single_price + `' readonly />
                    </div>
                  </li>
                  <li>
                    <div>
                      <label>单位：</label>
                      <input placeholder="请填写单位" name="unit" id="unit"
                      value='` + jsondrawer_products[i].pivot.unit + `'>
                    </div>
                  </li>

                  <li>
                    <div>
                      <label> <small class="red">*</small>报关金额：</label>
                      <input placeholder="请填写报关金额：" data-type="required" name="total_price" id="total_price"
                      value='` + jsondrawer_products[i].pivot.total_price + `'>
                    </div>
                  </li>
                  <li class="last_li">
                    <div>
                      <label><small class="red">*</small>开票金额(RMB)：</label>
                      <input placeholder="开票金额(RMB)"  data-type="required" name="value" id="value"
                      value='` + jsondrawer_products[i].pivot.value +`'>
                    </div>
                  </li>
                  <li>
                    <div>
                      <label><small class="red">*</small>申报单位：</label>
                      <input data-type="required" class="unit_openCon" id="measure_unit" data-form="1" readonly
                      value='` +jsondrawer_products[i].pivot.measure_unit_cn +`' readonly />
                      <input type="hidden" name="measure_unit" data-type="required" value='` +jsondrawer_products[i].pivot.measure_unit +`'>
                    </div>
                  </li>
                  <li class="last_li" style="width: 67%;">
                    <div>
                      <label><small class="red">*</small>法定数量和单位：</label>
                      <input placeholder="请填写数量" name="default_num" id="default_num"
                          value='` +(jsondrawer_products[i].pivot.default_num == null ? '':jsondrawer_products[i].pivot.default_num) +`' />
                      <input id="default_unit" class="unit_openCon" name="default_unit" placeholder="请填写法定单位" readonly
                        value='` +(jsondrawer_products[i].pivot.default_unit == null ? '':jsondrawer_products[i].pivot.default_unit) +`' />
                      <input type="hidden" name="default_unit_id" data-type="required" value='` +jsondrawer_products[i].pivot.default_unit_id +`' />
                    </div>
                  </li>
                  <li class="last_li" style="width:100%">
                    <div>
                      <label><small class="red">*</small>申报要素：</label>
                      <input class="standard" name="standard" value="` +(jsondrawer_products[i].product.standard == null ? '':jsondrawer_products[i].product.standard) +`" style="width: 60%;" />
                    </div>
                  </li>
                  <li style="width:100%;border-bottom: none">
                    <div style="float: right">
                      <a type="button" class="am-btn am-btn-default am-btn-xs kpr_del">删除</a>
                    </div>
                  </li>
                </ul>
              </div>
            </section>`
        }
        $('#pro_sec_box').html(html)
        $.get('/api/order/create', {
          token: $.cookie('token')
        }, function (res) {
          var data = res.data
          // ObjEach(data.unit, $('[name="default_unit"]'))
          // ObjEach(data.unit, $('[name="unit"]'))
        }).then(function () {
          jsondrawer_products.map(function (item, index) {
            $('[name="enjoy_offer"]:eq(' + index + ')').val(item.pivot.enjoy_offer).change()
            $('[name="goods_attribute"]:eq(' + index + ')').val(item.pivot.goods_attribute).change()
            $('[name="unit"]:eq(' + index + ')').val(item.pivot.unit).change()
          })
        })
        $('[name="enter_price_method"]').trigger('click')

        $(".removeClass").css("display", "inline")
        $(".removeClass").css("font-size", "12px")
        $(".removeClass").css("width", "150px")
        $('.pro-close').trigger('click')
      })
    }
  })

  //录入价格方式控制产品的单价和货值
  $('[name="enter_price_method"]').on('click', function () {
    var single_price = $('[name="single_price"]')
    single_price.map(function (index, item) {
      $('[name="single_price"]:eq(' + index + ')').attr('disabled', true)
    })
  })
  $(document).on('keyup', '.pro_sec input[name="number"]', function () {
    // 货值 = 数量 * 单价
    var a = $(this).val() * 1
    var total_price = $(this).parents('ul').find('input[name="total_price"]').val() * 1
    var c = (total_price / a).toFixed(4)
    $(this).parents('ul').find('input[name="single_price"]').val(c)
    // 产品数量 = 数量相加
    sum('number', '#total_num', 4)
    sum('total_price', '#total_value', 4)
  }).on('keyup', '.pro_sec input[name="single_price"]', function () {
    // 货值 = 单价 * 数量
    var a = $(this).val() * 1
    var number = $(this).parents('ul').find('input[name="number"]').val() * 1
    var c = (a.toFixed(4) * number.toFixed(4)).toFixed(4)
    $(this).parents('ul').find('#total_price').val(c)
    sum('total_price', '#total_value', 4)
}).on('keyup', '.pro_sec #total_price', function () {
    // 单价 = 货值 / 数量
    var a = $(this).val() * 1
    var number = $(this).parents('ul').find('input[name="number"]').val() * 1
    var c = (a.toFixed(4) / number.toFixed(4)).toFixed(4)
    sum('total_price', '#total_value', 4)
    $(this).parents('ul').find('input[name="single_price"]').val(c)
  }).on('keyup', '.pro_sec input[name="net_weight"]', function () {
    sum('net_weight', '#total_net_weight', 4)
  }).on('keyup', '.pro_sec input[name="volume"]', function () {
    sum('volume', '#total_volume', 4)
}).on('keyup', '.pro_sec #total_weight', function () {
    sum('total_weight', '.sum_weight', 4)
  }).on('keyup','.pro_sec input[name="pack_number"]',function(){
    sum('pack_number','#total_packnum',4)
  })


  //收货人相关按钮
  $(document).on('click', '.receive_btn', function () {
    var data = $('#receiveForm').serializeObject()
    $.post('/api/receive', {
      data: data,
      token: $.cookie('token')
    }, function (res) {
      if (res.code == 200) {
        layer.msg('添加成功')
        getReceive()
      } else {
        layer.msg(res.msg)
      }
    })
  }).on('click', '.receive_del', function () {

    var id = $('#receiveList tbody input:checked').val()
    if (id == undefined) {
      layer.msg('请选择一条数据进行删除')
      return false
    }
    $.ajax({
      type: 'delete',
      url: '/api/receive/' + id,
      data: {
        token: $.cookie('token')
      },
      dataType: 'json',
      success: function (res) {
        if (res.code == 200) {
          layer.msg('删除成功')
          getReceive()
        } else {
          layer.msg('删除失败')
        }
      }
    })
  }).on('click', '.new_receive', function () {
    getReceive()
  }).on('click', '.receivebutton', function () {
    var val = $("#receiveList input[type=radio]:checked").val()
    if (val == undefined) {
      layer.msg('请先选择一条数据')
      return false
    }
    var name = $("#receiveList input[type=radio]:checked").attr('rel')
    var country = $("#receiveList tbody input:checked").parents().siblings('.country_td').html()
    $("#receivep").html(
      '<p class="am-radio"><input type="radio" checked="checked" value="' + val +
      '"><small>' + name + '</small><small style="margin-left:10px;">' + country + '</small></p>')
    $('.receive-close').trigger('click')
    $('.receive').val(val)
  })

  //联系人相关按钮
  $(document).on('click', '.links_btn', function () {
    var data = $('#linksForm').serializeObject()
    $.post('/api/links', {
      data: data,
      token: $.cookie('token')
    }, function (res) {
      if (res.code == 200) {
        layer.msg('添加成功')
        getLinks()
      } else {
        layer.msg(res.msg)
      }
    })
  }).on('click', '.links_del', function () {
    var id = $('#linksList tbody input:checked').val()
    if (id == undefined) {
      layer.msg('请选择一条数据进行删除')
      return false
    }
    $.ajax({
      type: 'delete',
      url: '/api/links/' + id,
      data: {
        token: $.cookie('token')
      },
      dataType: 'json',
      success: function (res) {
        if (res.code == 200) {
          layer.msg('删除成功')
          getLinks()
        } else {
          layer.msg('删除失败')
        }
      }
    })
  }).on('click', '.new_links', function () {
    getLinks()
  }).on('click', '.links_update', function () {
    if ($(this).data('type') == '1') {
      $(this).parent().siblings().children('input[type=text]').removeAttr('readonly')
      $(this).data('type', '2')
      $(this).text('保存')
      $this = $(this)
    } else {
      $.ajax({
        type: 'PUT',
        url: '/api/links/' + $(this).data('id') / 1,
        data: {
          token: $.cookie('token'),
          data: {
            name: $(this).parents('tr').find('td:eq(1) input').val(),
            // country: $(this).parents('tr').find('td:eq(2) input').val(),
            // address: $(this).parents('tr').find('td:eq(3) input').val(),
            phone: $(this).parents('tr').find('td:eq(2) input').val()
          }
        },
        success: function (res) {
          layer.msg(res.msg)
          $this.parent().siblings().children('input[type=text]').attr('readonly', 'true')
          $this.data('type', '1')
          $this.text('修改')
          getLinks()
        }
      })
    }
  }).on('click', '.links_sub', function () {
    var val = $("#linksList input[type=radio]:checked").val()
    var name = $("#linksList input[type=radio]:checked").attr('rel')
    if (name == undefined) {
      layer.msg('请先选择一条数据')
      return false
    }
    var phone = $("#linksList tbody input:checked").parents().siblings('.phone_input').find('input').val()
    $("#liken").html(
      '<p class="am-radio"><input type="radio" checked="checked" value="' + val +
      '"><small>' + name + '</small><small style="margin-left:10px;">' + phone + '</small></p>')
    $('.links-close').trigger('click')
    $('.links').val(val)
  })

  //存货地相关按钮
  $(document).on('click', '.deposit_btn', function () {
    var data = $('#depositForm').serializeObject()
    $.post('/api/deposit', {
      data: data,
      token: $.cookie('token')
    }, function (res) {
      if (res.code == 200) {
        layer.msg('添加成功')
        getDeposit()
      } else {
        layer.msg(res.msg)
      }
    })
  }).on('click', '.deposit_del', function () {
    var id = $('#depositList tbody input:checked').val()
    if (id == undefined) {
      layer.msg('请选择一条数据进行删除')
      return false
    }
    $.ajax({
      type: 'delete',
      url: '/api/deposit/' + id,
      data: {
        token: $.cookie('token')
      },
      dataType: 'json',
      success: function (res) {
        if (res.code == 200) {
          layer.msg('删除成功')
          getDeposit()
        } else {
          layer.msg('删除失败')
        }
      }
    })
  }).on('click', '.new_deposit', function () {
    getDeposit()
  }).on('click', '.deposit_sub', function () {
    var val = $("#depositList input[type=radio]:checked").val()
    if (val == undefined) {
      layer.msg('请先选择一条数据')
      return false
    }
    var name = $("#depositList input[type=radio]:checked").attr('rel')
    var address = $("#depositList input[type=radio]:checked").attr('address')
    $("#Storage_address").html('<p class="am-radio"><input type="radio" name="" checked="checked" value="' +
      val + '"><small class="">' + name +
      '</small><small name="storage_phone" style="margin-left:10px;">' + address + '</small></p>')
    $('.deposit-close').trigger('click')
    $('.deposit').val(val)
  })
})
$('#shipping_at').on('change', function () {
  $(this).val($(this).attr('value'))
}).change()
$('#sailing_at').on('change', function () {
  $(this).val($(this).attr('value'))
}).change()
//选择开票人
$(document).on('click', '.drawer_layer', function () {
  $this = $(this)
  layer.open({
    type: 1,
    title: '选择开票人',
    area: ['600px', '450px'],
    btn: ['确定', '取消'],
    content: `<table class="am-table am-table-bordered am-table-radius" id="drawer_table">
          <thead>
            <tr>
              <th>
                <small>开票人名称</small>
              </th>
              <th>
                <small>纳税人识别号</small>
              </th>
              <th>
                <small>提交时间</small>
              </th>
              <th>
                <small>状态</small>
              </th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
        <div class="purpage page_turn">
          <ul id="page"></ul>
        </div>`,
    success: function () {
      getDrawers()
    },
    btn1: function (i) {
      if ($('#drawer_table tbody tr.active').length < 0) {
        layer.msg('请至少选择一条数据')
        return false
      }
      $this.siblings('.drawer').children('input').val($('#drawer_table tbody tr.active td:eq(0)').text())
      $this.prev().val($('#drawer_table tbody tr.active').data('id'))
      layer.close(i)
    }
  })
})

//增加产品
$(document).on('click', '.add_orderPro', function () {
  getProList(1)
})

//添加一个产品表单
$(document).on('click', '.submit_pro', function () {
  if ($('#product_list tbody tr.active').length < 1) {
    layer.msg('请至少选择一条数据')
    return false
  }
  var dra_pro_id = $("#product_list .active").data('id')
  var standard, name, measure_unit, measure_unit_cn, en_name, hscode, tax_refund_rate, tax_rate, source, domestic_source_id
  $.ajax({
    type: 'get',
    url: '/api/drawer_products/detail/' + dra_pro_id,
    data: {
      token: $.cookie('token')
    },
    success: function (data) {
      //console.log(data.data.brand.type_str)
      var data = data.data
      name = data.name
      company = data.company
      en_name = data.en_name
      source = data.domestic_source
      domestic_source_id = data.domestic_source_id
      hscode = data.hscode
      brand = data.brand_type_str
      brand_classify_str = data.brand_classify_str
      tax_refund_rate = data.tax_refund_rate
      tax_rate = data.tax_rate
      measure_unit = data.measure_unit
      measure_unit_cn = data.measure_unit_cn
      unit = data.unit
      unit_id = data.unit_id
      standard = data.standard
      var html = ''
      var index = $('section').length
      html =`<section class="am-panel am-panel-default">
              <header class="am-panel-hd">
                <h5 class="am-panel-title">产品名称：
                    <input readonly id="name" style="width:50%" value="${name}(${company})">
                    <input type="hidden" id="company" name="company" value="${company}">
                </h5>
              </header>
              <div class="pro_sec">
                <ul class="clearfix" style="min-width: 1000px;">
                  <li>
                    <div>
                      <label>产品分类：</label>
                      <input name="drawer_product_id" type="hidden" id="id" value="${dra_pro_id}">
                      <input id="brand_type" value="${brand_classify_str}" disabled="disabled">
                    </div>
                  </li>
                  <li>
                    <div>
                      <label>品牌类型：</label>
                      <input id="brand" value='${brand}' disabled="disabled">
                    </div>
                  </li>
                  <li class="last_li">
                    <div>
                      <label><small class="red">*</small>英文品名：</label>
                      <input id="en_name" data-type="required" value='${en_name}' disabled="disabled">
                    </div>
                  </li>
                  <li>
                    <div>
                      <label><small class="red">*</small>境内货源地：</label>
                      <input placeholder="请选择境内货源地" data-type="required" name="domestic_source" id="domestic_source" class="domestic_source_con"
                       value="${source}" style="width:70%" readonly />
                      <input type="hidden" name="domestic_source_id" value="${domestic_source_id}">
                    </div>
                  </li>
                  <li>
                    <div>
                      <label>产地：</label>
                      <input placeholder="请填写产地" name="production_place" id="production_place">
                    </div>
                  </li>
                  <li  class="last_li">
                    <div>
                      <label><small class="red">*</small>原产国(地区)：</label>
                      <input placeholder="请填写原产国" data-type="required" name="origin_country" id="origin_country" class="country_openCon" value="中国" readonly />
                      <input type="hidden" name="origin_country_id" value="774">
                    </div>
                  </li>
                  <li>
                    <div>
                      <label><small class="red">*</small>最终目的国(地区)： </label>
                      <input placeholder="请填写最终目的国" data-type="required" value=""
                      name="destination_country" id="destination_country" class="country_openCon" readonly />
                      <input type="hidden" name="destination_country_id" value="">
                    </div>
                  </li>
                  <li>
                    <div>
                      <label><small class="red">*</small>货物属性：</label>
                      <select class="removeClass" name="goods_attribute" data-type="required" id="goods_attribute">
                        <option value="0">自产</option>
                        <option value="1">委托加工</option>
                        <option value="2">外购</option>
                      </select>
                    </div>
                  </li>
                  <li class="last_li">
                    <div>
                      <label><small class="red">*</small>HSCode：</label>
                      <input placeholder="请填写HSCode" data-type="required" id="HSCode" value="${hscode}" disabled="disabled">
                    </div>
                  </li>
                  <li>
                    <div>
                      <label>CIQ编码：</label>
                      <input name="ciq_code" id="ciq_code">
                    </div>
                  </li>
                  <li>
                    <div>
                      <label>退税率：</label>
                        <input name="tax_refund_rate" id="tax_refund_rate" value="${tax_refund_rate}" disabled="disabled">
                    </div>
                  </li>
                  <li class="last_li">
                    <div>
                      <label>税率：</label>
                        <input name="tax_rate" id="tax_rate" value="${tax_rate}" disabled="disabled">
                    </div>
                  </li>
                  <li>
                    <div>
                      <label>种类：</label>
                      <input placeholder="请填写种类" name="species" id="species">
                    </div>
                  </li>
                  <li>
                    <div>
                      <label>表面材质：</label>
                      <input placeholder="请填写表面材质" name="surface_material" id="surface_material">
                    </div>
                  </li>
                  <li class="last_li">
                    <div>
                      <label>款号：</label>
                      <input placeholder="请填写款号" name="section_number" id="section_number">
                    </div>
                  </li>
                  <li class="last_li" style="width:100%">
                    <div>
                      <label>出口货物在最终目的国（地区）是否享受优惠关税：</label>
                      <select class="removeClass" name="enjoy_offer" id="enjoy_offer">
                        <option>-- 请选择 --</option>
                        <option value="0">享受</option>
                        <option value="1">不享受</option>
                        <option value="2">不能确定</option>
                      </select>
                    </div>
                  </li>
                  <li>
                    <div>
                      <label><small class="red">*</small>最大包装件数：</label>
                      <input placeholder="请填写最大包装件数" name="pack_number" data-type="required" id="pack_number">
                    </div>
                  </li>
                  <li>
                    <div>
                      <label> <small class="red">*</small>净重(KG)：</label>
                      <input placeholder="请填写净重(KG)" data-type="required" name="net_weight" id="net_weight">
                    </div>
                  </li>
                  <li class="last_li">
                    <div>
                      <label> <small class="red">*</small>毛重(KG)：</label>
                      <input placeholder="请填写总毛重(KG)" data-type="required"  name="total_weight" id="total_weight">
                    </div>
                  </li>
                  <li>
                    <div>
                      <label> <small class="red">*</small>体积(CBM)：</label>
                      <input placeholder="请填写体积(CBM)" data-type="required" name="volume" id="volume">
                    </div>
                  </li>
                  <li>
                    <div>
                      <label> <small class="red">*</small>产品数量：</label>
                      <input placeholder="请填写产品数量" data-type="required" name="number" id="number">
                    </div>
                  </li>
                  <li class="last_li">
                    <div>
                      <label>单价(USD)：</label>
                      <input placeholder="请填写单价(USD)" name="single_price" id="single_price" readonly />
                    </div>
                  </li>
                  <li>
                    <div>
                      <label>单位：</label>
                      <input placeholder="请填写单位" name="unit" id="unit">

                    </div>
                  </li>
                  <li>
                    <div>
                      <label> <small class="red">*</small>报关金额：</label>
                      <input placeholder="请填写报关金额：" data-type="required" name="total_price" id="total_price">
                    </div>
                  </li>
                  <li class="last_li">
                    <div>
                      <label><small class="red">*</small>开票金额(RMB)：</label>
                      <input placeholder="开票金额(RMB)" data-type="required" name="value" id="value">
                    </div>
                  </li>
                  <li>
                    <div>
                      <label><small class="red">*</small>申报单位：</label>
                      <input data-type="required" class="unit_openCon" value="${measure_unit_cn}" id="measure_unit" readonly />
                      <input type="hidden" name="measure_unit" data-type="required" value="${measure_unit}">
                    </div>
                  </li>
                  <li class="last_li" style="width: 67%;">
                    <div>
                      <label><small class="red">*</small>法定数量和单位：</label>
                      <input placeholder="请填写数量" name="default_num" id="default_num">
                      <input id="default_unit" class="unit_openCon" name="default_unit" value="${unit}" placeholder="请填写法定单位" readonly />
                      <input type="hidden" name="default_unit_id" data-type="required" value='${unit_id}'>
                    </div>
                  </li>
                  <li class="last_li" style="width:100%">
                    <div>
                      <label><small class="red">*</small>申报要素：</label>
                      <input class="standard" name="standard" value="${standard}" style="width: 60%;" />
                    </div>
                  </li>
                  <li style="width:100%;border-bottom: none">
                    <div style="float: right">
                      <a type="button" class="am-btn am-btn-default am-btn-xs kpr_del">删除</a>
                    </div>
                  </li>
                </ul>
              </div>
            </section>`
      // <a type="button" class="am-btn am-btn-default am-btn-xs">复制</a>
      $('#pro_sec_box').append(html)
      $('[name="enter_price_method"]').trigger('click')
      $(".removeClass").css("display", "inline")
      $(".removeClass").css("font-size", "12px")
      $(".removeClass").css("width", "150px")
      $('.pro-close').trigger('click')
    }
  })
}).on('click', '.kpr_del', function () {
  $(this).parents('section').remove()
  if($(document).find('section').length){
      $('#net_weight,#total_weight,#volume,#number,#total_price,#pack_number').trigger('keyup')
  } else {
      $('.clear_this').val('0.0000')
  }
})

//贸易国及运抵国选择
$(document).on('change', '#aim_country_name', function () {
    $this = $(this)
    $('[name="destination_country"]').val($this.val())
    $('#unloading_port_name').val('')
    $('#unloading_port').val('')
    $.get('/api/port?country=' + $('#aim_country').val(), {
        token: $.cookie('token')
    }, function (res) {
        var html = ''
        if (res.code == 200) {
            for (var i in res.data) {
                html += `<li data-port="${i}">${res.data[i]}</li>`
            }
            $('.port_div ul').html(html)
        } else {
            layer.msg(res.code + res.msg)
        }
    })
}).on('click', '#unloading_port_name', function () {
    if($('#aim_country_name').val() == ''){
        layer.msg('请先选择运抵国')
        return false
    }
    $('.port_div').show()
    $('.serach_upport').focus()
}).on('click', '#aim_country_name, #trade_country_name', function () {
    if($(this).data('choose') == 'aimCountry'){
        $('.country_div').show()
        $('.serach_country').focus()
        countrySearch('country_div','')
    } else {
        $('.trade_country_div').show()
        $('.serach_trade_country').focus()
        countrySearch('trade_country_div','')
    }
})
let last
$(document).on('keyup', '.serach_trade_country', function (e) {
    last = e.timeStamp
    let val = $(this).val()
    setTimeout(function () {
        if(last-e.timeStamp == 0){
            countrySearch('trade_country_div',val)
        }
    }, 500)
})
$(document).on('keyup', '.serach_country', function (e) {
    last = e.timeStamp
    let val = $(this).val()
    setTimeout(function () {
        if(last-e.timeStamp == 0){
            countrySearch('country_div',val)
        }
    }, 500)
})
//搜索国家
function countrySearch(div_class,value){
    $.get('/api/country', {
      token: $.cookie('token'),
      keyword: value
    }, function (res) {
      var html = ''
      if (res.code == 200) {
        for (var i in res.data) {
          html += `<li data-port="${i}">${res.data[i]}</li>`
        }
        $('.'+ div_class +' ul').html(html)
      } else {
        layer.msg('没有指定国家')
      }
    })
}
//搜索指运港
function upportSearch(div_class,value){
    $.get('/api/port?country=' + $('#aim_country').val(), {
      token: $.cookie('token'),
      keyword: value
    }, function (res) {
      var html = ''
      if (res.code == 200) {
        for (var i in res.data) {
          html += `<li data-port="${i}">${res.data[i]}</li>`
        }
        $('.'+ div_class +' ul').html(html)
      } else {
        layer.msg('没有指定港口')
      }
    })
}
$(document).on('click', '.country_div li', function () {
  // $(this).addClass('orange').siblings().removeClass('orange')
  $('#aim_country').val($(this).data('port'))
  $('#aim_country_name').val($(this).text()).trigger('change')
  $('.country_div').hide()
  $('.country_div ul').html('')
  $('.serach_country').val('')
}).on('click', '.trade_country_div li', function () {
    // $(this).addClass('orange').siblings().removeClass('orange')
    $('#trade_country').val($(this).data('port'))
    $('#trade_country_name').val($(this).text()).trigger('change')
    $('.trade_country_div').hide()
    $('.trade_country_div ul').html('')
    $('.serach_trade_country').val('')
})
//失去焦点隐藏div
$('#trade_country_name, #aim_country_name, #unloading_port_name').on('click',function(){
    if($(this).data('choose') == 'aimCountry'){
        $('.trade_country_div, .port_div').hide()
        $('.trade_country_div ul, .port_div ul').html('')
    } else if($(this).data('choose') == 'tradeCountry'){
        $('.country_div, .port_div').hide()
        $('.country_div ul, .port_div ul').html('')
    } else {
        $('.trade_country_div, .country_div').hide()
        $('.trade_country_div ul, .country_div ul').html('')
    }
})

//指运港选择
$(document).on('keyup', '.serach_upport', function (e) {
    last = e.timeStamp
    let val = $(this).val()
    setTimeout(function () {
        if(last-e.timeStamp == 0){
            upportSearch('port_div',val)
        }
    }, 500)
}).on('click', '.port_div li', function () {
  // $(this).addClass('orange').siblings().removeClass('orange')
  $('#unloading_port').val($(this).data('port'))
  $('#unloading_port_name').val($(this).text())
  $('.port_div').hide()
})

//选择境内货源地
$(document).off('click','.domestic_source_con')
$(document).on('click','.domestic_source_con',function () {
    var ele,th=$(this),
        outerTitle = '选择境内货源地'
    var index = layer.open({
        type: 1,
        title: outerTitle,
        area: '800px',
        offset: 'top',
        content: $('#districtlist'),
        btn: ['确定'],
        success: function (layero, index) {
            $('.district_name').val('')
            getDistrict(1)
            let id = ''
                name = ''
            layero.on('dblclick','tbody tr',function(){
                id = $(this).data('id')
                name = $(this).data('name')
                th.val(name)
                th.next().val(id)
                layer.close(index)
            })
        },
        yes: function (i) {
            let id = ele.find('.active').data('id')
            district_name = ele.find('.active .district_name').text()
            th.val(district_name)
            th.next().val(id)
            layer.close(i)
        }
    })
    ele = $('#layui-layer'+index)
})
$('.search_dis').on('click',function(){
    getDistrict(1)
})

// 选择国家
$(document).off('click','.country_openCon')
$(document).on('click','.country_openCon',function () {
    var ele,th=$(this),
        outerTitle = '选择国家'
    var index = layer.open({
        type: 1,
        title: outerTitle,
        area: '800px',
        offset: 'top',
        content: $('#countrylist'),
        btn: ['确定'],
        success: function (layero,index) {
            $('.country_name').val('')
            getCountry(1)
            let id = ''
                name = ''
            layero.on('dblclick','tbody tr',function(){
                id = $(this).data('id')
                name = $(this).data('name')
                th.val(name)
                th.next().val(id)
                layer.close(index)
            })
        },
        yes: function (i) {
            let id = ele.find('.active').data('id')
            country_name = ele.find('.active .country_name').text()
            th.val(country_name)
            th.next().val(id)
            layer.close(i)
        }
    })
    ele = $('#layui-layer'+index)
})
$('.search_cou').on('click',function(){
    getCountry(1)
})

$('.search_unit').on('click',function(){
    getUnit(1)
})
$(document).off('click','.unit_openCon')
$(document).on('click','.unit_openCon',function () {
    //选择单位
    var ele,th=$(this),
        outerTitle = '选择单位'
    var index = layer.open({
        type: 1,
        title: outerTitle,
        area: '800px',
        offset: 'top',
        content: $('#unitlist'),
        btn: ['确定'],
        success: function (layero,index) {
            $('.unit_name').val('')
            getUnit(1)
            let id = ''
                name = ''
            layero.on('dblclick','tbody tr',function(){
                id = $(this).data('id')
                name = $(this).data('name')
                th.val(name)
                th.next().val(id)
                layer.close(index)
            })
        },
        yes: function (i) {
            let id = ele.find('.active').data('id')
            unit_name = ele.find('.active .unit_name').text()
            console.log();
            th.val(unit_name)
            th.next().val(id)
            layer.close(i)
        }
    })
    ele = $('#layui-layer'+index)
})

function getDistrict(page){
    var api = '/api/district/list';
        keyword =  $('.district_name').val()
    $.ajax({
        url: api,
        type: 'get',
        data: {
            token: $.cookie('token'),
            page: page,
            keyword: keyword
        },
        success: function (res) {
            var total = res.data.total
                html = ''
            $('.districtList').html("");
            $.each(res.data.data,function (a,b) {
                html += '<tr data-id="' + b.id + '" data-name="'+b.district_name+'">' +
                    '<td class="district_name">' + b.district_name + '</td>' +
                    '<td class="">' + b.district_code + '</td>'
                '</tr>';
            })
            $('.districtList').html(html);
            //调用分页
            crepage($('#district_page'), res.data.currentPage, res.data.total, getDistrict)
        }
    })
}
function getCountry(page){
    var api = '/api/country/list';
        keyword =  $('.country_name').val()
    $.ajax({
        url: api,
        type: 'get',
        data: {
            token: $.cookie('token'),
            page: page,
            keyword: keyword
        },
        success: function (res) {
            var total = res.data.total
                html = ''
            $('.countryList').html("");
            $.each(res.data.data,function (a,b) {
              html += '<tr data-id="' + b.id + '" data-name="'+b.country_na+'">' +
                '<td class="country_name">' + b.country_na + '</td>' +
                '<td class="">' + b.country_en + '</td>' +
                '<td class="">' + b.country_co + '</td>' +
                '</tr>';
            })
            $('.countryList').html(html);
            //调用分页
            crepage($('#country_page'), res.data.currentPage, res.data.total, getCountry)
        }
    })
}
function getUnit(page){
    var api = '/api/unit/list';
        keyword =  $('.unit_name').val()
    $.ajax({
        url: api,
        type: 'get',
        data: {
            token: $.cookie('token'),
            page: page,
            keyword: keyword
        },
        success: function (res) {
            var total = res.data.total
                html = ''
            $('.unitList').html("");
            $.each(res.data.data,function (a,b) {
              html += '<tr data-id="' + b.id + '" data-name="'+b.name+'">' +
                '<td class="unit_name">' + b.name + '</td>' +
                '<td class="">' + b.value + '</td>' +
                '</tr>';
            })
            $('.unitList').html(html);
            //调用分页
            crepage($('#Unit_page'), res.data.currentPage, res.data.total, getUnit)
        }
    })
}

//提交订单 保存草稿
$("#submit,#save").click(function () {
    var input = [],
        select = []
    input = $('input[data-type="required"]')
    select = $('select[data-type="required"]')
    var num = 0
    if($(this).data('type') == 2){
        input.map(function (i, item) {
            if (item.value == '') {
                num === 1 ? '' : layer.msg(item.placeholder)
                num = 1
                return false;
            }
        })
        if (num == 1) {
            return false
        }
        if ($('#links').val() == '') {
            layer.msg('请先选择本单联系人')
            return false
        }
        if ($('section').length == 0) {
            layer.msg('请添加产品')
            return false
        }
        if ($('#receive').val() == '') {
            layer.msg('请先选择境外收货人')
            return false
        }
        if ($('#deposit').val() == '') {
            layer.msg('请先选择货物存放地址')
            return false
        }
    }
    var data = $("#cusform").serializeObject()
    var data2 = $("#pro_form section").serializeObjArr()
    var data3 = $("#three_form").serializeObject()
    var id = getUrlParam("id")
    data = $.extend(data, data3)
    data.pro = data2
    if ($(this).data('type') == '2') {
        data.status = 2
        if (id != null && id != '' && getUrlParam('type') == undefined) {
            $.ajax({
                type: 'post',
                url: '/api/order/update/' + id + '?token=' + $.cookie('token'),
                data: data,
                success: function (res) {
                    if (res.code == 200) {
                        layer.msg("提交成功")
                        $('a.close_a span').trigger('click')
                    } else {
                        layer.msg("提交失败" + res.msg)
                    }
                }
            })
        } else {
            $.ajax({
                type: 'post',
                url: '/api/order/save?token=' + $.cookie('token'),
                data: data,
                success: function (res) {
                    if (res.code == 200) {
                        layer.msg("提交成功")
                        $('a.close_a span').trigger('click')
                    } else {
                        layer.msg("提交失败" + res.msg)
                    }
                }
            })
        }
    } else {
        if (id != null && id != '' && getUrlParam('type') == undefined) {
            data.status = 1
            var key = "token";
            var value = $.cookie('token')
            data[key] = value;
            $.ajax({
                type: 'post',
                url: '/api/order/draft_update/' + id,
                data: data,
                success: function (res) {
                    if (res.code == 200) {
                        layer.msg("保存成功")
                        $('a.close_a span').trigger('click')
                    } else {
                        layer.msg("保存失败" + res.msg)
                    }
                }
            })
        } else {
            data.status = 1
            var key = "token";
            var value = $.cookie('token')
            data[key] = value;
            $.ajax({
                type: 'post',
                url: '/api/order/draft_save',
                data: data,
                success: function (res) {
                    if (res.code == 200) {
                        layer.msg("保存成功")
                        $('a.close_a span').trigger('click')
                    } else {
                        layer.msg("保存失败" + res.msg)
                    }
                }
            })
        }
    }
})
$("#net_one").click(function () {
    $("#1").hide(500);
    $("#net_one").hide();
    $("#net_one_show").show();
    $("#2").show();
    $("#net_twoshow").show();
})
$("#net_one_show").click(function () {
    $("#1").show(500);
    $("#net_one_show").hide();
    $("#net_one").show();
})
$("#net_twoshow").click(function () {
    let flag = true
    $('.pro_sec input[data-type="required"]').each(function(){
        if($(this).val() == ''){
            flag = false
            return false
        } else {
            flag = true
        }
    })
    if(flag){
        $("#2").hide(1000);
        $("#net_twoshow").hide();
        $("#net_twotoggle").show();
        $("#3").show(1000);
    } else {
        layer.msg('请填写完整的产品信息')
    }
})
$("#net_twotoggle").click(function () {
    $("#2").show(1000);
    $("#net_twoshow").show();
    $("#net_twotoggle").hide();
})
$("#declaration1").click(function () {
    $("#paper").hide();
    $("#nopaper").show();
})
$("#declaration2").click(function () {
    $("#nopaper").hide();
    $("#paper").show();
})
$("#cif").click(function () {
    $("#money").show();
    $("#money1").show();
})
$("#fob").click(function () {
    $("#money").hide();
    $("#money1").hide();
})
$("#cif1").click(function () {
    $("#money").show();
    $("#money1").hide();
})
$("#remit").click(function () {
    $("#lcnumber").hide();
})
$("#collection").click(function () {
    $("#lcnumber").hide();
})
$("#credit").click(function () {
    $("#lcnumber").show();
})
$("#tick").click(function () {
    $("#lcnumber").hide();
})
$("#khbg").click(function () {
    $("#customsbroker").show();
})
$("#sxgs").click(function () {
    $("#customsbroker").hide();
})
$(".removeClass").css("display", "inline")
$(".removeClass").css("font-size", "12px")
$(".removeClass").css("width", "150px")
