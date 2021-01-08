<style>
  .gc_file_list{
    display: inline;
    vertical-align: top;
    padding: 0;
    margin: 0;
  }
  .gc_file_list .gc_item{
    overflow: hidden;
    background-color: #fff;
    border: 1px solid #c0ccda;
    border-radius: 6px;
    box-sizing: border-box;
    width: 148px;
    height: 148px;
    margin: 0 8px 8px 0;
    display: inline-block;
    position: relative;
  }
  .gc_file_list .gc_item img{
    width: 100%;
    height: 100%;
  }
  .imgList .gc_item{
    width: 75px !important;
    height: 75px !important;
  }
  .gc_file_list .gc_item .gc_item_action:hover {
    opacity: 1;
  }
  .gc_file_list .gc_item .gc_item_action{
    position: absolute;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    cursor: default;
    text-align: center;
    color: #fff;
    opacity: 0;
    font-size: 20px;
    background-color: rgba(0, 0, 0, 0.5);
    transition: opacity .3s;
  }
  .gc_preview,.gc_download{
    display: inline-block;
    cursor: pointer;
    font-size: 1.2em;
    margin: 36% 10px 0;
  }
  .enclosureList{
    margin: 20px 0 0 20px;
  }
</style>
<div class="purdetail">
    <form class="form-horizontal cusName" style="overflow: hidden;">
        <input type="hidden" name="status">
        <input type="hidden" class="erpUrl" value="{{config('app.erp_url') .'/'}}" />
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">合同编号</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ isset($purchase->purchase_code) ? $purchase->purchase_code : ''}}" disabled/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">供方名称</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ isset($purchase->supplier) ? $purchase->supplier : ''}}" disabled/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">供方地址</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ isset($purchase->supplier_addr) ? $purchase->supplier_addr : ''}}" disabled/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">签订日期</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ isset($purchase->sign_date) ? $purchase->sign_date : ''}}" disabled/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">交货日期</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ isset($purchase->delivery_date) ? $purchase->delivery_date : ''}}" disabled/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">联系人</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ isset($purchase->link_name) ? $purchase->link_name : ''}}" disabled/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">电话</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ isset($purchase->link_phone) ? $purchase->link_phone : ''}}" disabled/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">QQ/微信</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ isset($purchase->link_qq) ? $purchase->link_qq : ''}}" disabled/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">付款时间(天)</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ isset($purchase->payment_time) ? $purchase->payment_time : ''}}" disabled/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">收款人户名</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ isset($purchase->bank_account) ? $purchase->bank_account : ''}}" disabled/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">收款人账号</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ isset($purchase->bank_number) ? $purchase->bank_number : ''}}" disabled/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">开户行</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ isset($purchase->bank_deposit) ? $purchase->bank_deposit : ''}}" disabled/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">定金(元)</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ isset($purchase->deposit) ? $purchase->deposit : ''}}" disabled/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">产品描述</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ isset($purchase->about) ? $purchase->about : ''}}" disabled/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">采购员</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ isset($purchase->buyer) ? $purchase->buyer : ''}}" disabled/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">业务员</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ isset($purchase->salesman) ? $purchase->salesman : ''}}" disabled/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <div class="col-sm-4">
                    <input type="button" class="form-control checkImg" value="查看附件" />
                </div>
            </div>
        </div>
        <div class="col-sm-12 m-t-xs">
            <div class="form-group">
                <div class="table-responsive panel" style="overflow-x:auto;margin:0 15px;">
                    <table class="table table-hover b-t b-light"id="ice" style="table-layout: fixed;min-width: 1050px;">
                        <thead>
                        <tr class="info">
                            <th width="8%">图片</th>
                            <th width="8%">货号</th>
                            <th width="8%">产品名称</th>
                            <th width="8%">尺寸/克重</th>
                            <th width="8%">颜色</th>
                            <th width="8%">材质</th>
                            <th width="8%">包装方式</th>
                            <th width="8%">单位</th>
                            <th width="8%">件数</th>
                            <th width="8%">装箱量</th>
                            <th width="8%">总量</th>
                            <th width="9%">不含税单价/元</th>
                            <th width="14%">总价/元</th>
                            <th width="10%">体积</th>
                            <th width="6%">总体积</th>
                            <th width="9%">备注</th>
                        </tr>
                        </thead>
                        <tbody class="otbody" >
                        @if (isset($productList))
                            @foreach($productList as $k => $v)
                                <tr data-id="{{ $v->id }}">
                                    <input type="hidden" value="{{$v->id}}">
                                    <td data-outwidth="850px"><ul class="gc_file_list imgList"></ul></td>
                                    <input type="hidden" class="imgUrl" value="{{$v->img}}">
                                    <td>{{$v->pro_number}}</td>
                                    <td>{{$v->name}}</td>
                                    <td>{{$v->size}}</td>
                                    <td>{{$v->color}}</td>
                                    <td>{{$v->material}}</td>
                                    <td>{{$v->package}}</td>
                                    <td>{{$v->unit}}</td>
                                    <td>{{$v->quantity}}</td>
                                    <td>{{$v->box_quantity}}</td>
                                    <td>{{$v->total_quantity}}</td>
                                    <td>{{$v->price}}</td>
                                    <td>{{$v->total_price}}</td>
                                    <td>{{$v->volume}}</td>
                                    <td>{{$v->total_volume}}</td>
                                    <td>{{$v->about}}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="line line-dashed"></div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label">审核结果</label>
                <div class="col-sm-8">
                    {{ Form::select('', ['2'=>'审核通过', '3'=>'审核拒绝'], $purchase->refundable ? $purchase->refundable : '', [
                        'class' => 'form-control result',
                        'name' => 'refundable',
                        'data-parsley-required',
                        'data-parsley-trigger' => 'change',
                        $purchase->refundable == 1 ? '' : 'disabled',
                    ]) }}
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label">审核日期</label>
                <div class="col-sm-8">
                    <input type="text" name="approved_at" {{ $purchase->refundable == 1 ? '' : 'disabled' }} value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="form-control" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-12 m-t-sm">
            <div class="form-group">
                <label class="col-sm-1 control-label" style="padding:7px 0;">审核意见</label>
                <div class="col-sm-11">
                    <textarea name="opinion" class="form-control opinion" {{ $purchase->refundable == 1 ? '' : 'disabled' }} rows="3">{{ isset($purchase->opinion) ? $purchase->opinion : '' }}</textarea>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="enclosure hide" id="enclosure">
  <div class="enclosureList">
    <div class="business_license gc_file_list">
      <h2>营业执照：</h2>
      <ul class="business_license_List gc_file_list"></ul>
    </div>
    <div class="sales_invoice gc_file_list">
      <h2>销项发票：</h2>
      <ul class="sales_invoice_List gc_file_list"></ul>
    </div>
    <div class="receipts_invoice gc_file_list">
      <h2>进项发票：</h2>
      <ul class="receipts_invoice_List gc_file_list"></ul>
    </div>
    <div class="factory_brand gc_file_list">
      <h2>厂牌：</h2>
      <ul class="factory_brand_List gc_file_list"></ul>
    </div>
    <div class="production_line gc_file_list">
      <h2>生产流水线：</h2>
      <ul class="production_line_List gc_file_list"></ul>
    </div>
    <div class="water_ele_invoice gc_file_list">
      <h2>水电费发票：</h2>
      <ul class="water_ele_invoice_List gc_file_list"></ul>
    </div>
    <div class="vat_declaration_form gc_file_list">
      <h2>增值税纳税申报表：</h2>
      <ul class="vat_declaration_form_List gc_file_list"></ul>
    </div>
  </div>
  <input type="hidden" class="business" value="{{ isset($purchase->business_license) ? $purchase->business_license : ''}}"/>
  <input type="hidden" class="sales" value="{{ isset($purchase->sales_invoice) ? $purchase->sales_invoice : ''}}"/>
  <input type="hidden" class="receipts" value="{{ isset($purchase->receipts_invoice) ? $purchase->receipts_invoice : ''}}"/>
  <input type="hidden" class="factory" value="{{ isset($purchase->factory_brand) ? $purchase->factory_brand : ''}}"/>
  <input type="hidden" class="production" value="{{ isset($purchase->production_line) ? $purchase->production_line : ''}}"/>
  <input type="hidden" class="water_ele" value="{{ isset($purchase->water_ele_invoice) ? $purchase->water_ele_invoice : ''}}"/>
  <input type="hidden" class="vat_declaration" value="{{ isset($purchase->vat_declaration_form) ? $purchase->vat_declaration_form : ''}}"/>
</div>
<script>
  //审核图片渲染
  var imgList = $('.imgList');
      imgUrl = $('.imgUrl').val();
      erpUrl = $('.erpUrl').val();
  randerImage2(imgList,imgUrl);

  //附件图片渲染
  var business = $('.business_license_List');
      businessUrl = $('.business').val();
      sales = $('.sales_invoice_List');
      salesUrl = $('.sales').val();
      receipts = $('.receipts_invoice_List');
      receiptsUrl = $('.receipts').val();
      factory = $('.factory_brand_List');
      factoryUrl = $('.factory').val();
      production = $('.production_line_List');
      productionUrl = $('.production').val();
      water_ele = $('.water_ele_invoice_List');
      water_eleUrl = $('.water_ele').val();
      vat_declaration = $('.vat_declaration_form_List');
      vat_declarationUrl = $('.vat_declaration').val();
  randerImage2(business,businessUrl);
  randerImage2(sales,salesUrl);
  randerImage2(receipts,receiptsUrl);
  randerImage2(factory,factoryUrl);
  randerImage2(production,productionUrl);
  randerImage2(water_ele,water_eleUrl);
  randerImage2(vat_declaration,vat_declarationUrl);

  $(document).on('click','.checkImg',function () {
    var width=parseInt($(document).width()*0.6)+'px',
        height=parseInt($(document).height()*0.6)+'px'
        enclosure = $('#enclosure').html()
    layer.open({
      title: false,
      type: 1,
      area: [width, height],
      content: enclosure //数组第二项即吸附元素选择器或者DOM
    });
  })
  function randerImage2(target, imgList, glue) {
    glue = glue || '|';
    if (!imgList) {
      return false;
    }
    if (typeof imgList !== 'string') {
      return layer.alert('地址只能是字符串!');
    }
    imgList = imgList.split(glue);
    pic_ext = 'jpg,png,gif,jpeg';
    $.each(imgList,function (i,item) {
      var ext = imgList[i].substr(imgList[i].lastIndexOf('.')+1).toLowerCase();
      if(pic_ext.indexOf(ext) != -1){
          html = '<li class="gc_item"> ' +
          '<img src="' + erpUrl + imgList[i] + '"> ' +
          '<span class="gc_item_action"> ' +
          '<span class="gc_preview">预览</span> ' +
          '</span> ' +
          '</li>'
          target.append(html);
      }else{
          html = '<li class="gc_item"> ' +
          '<img src="' + erpUrl +'static/img/load.jpg"> ' +
          '<span class="gc_item_action"> ' +
          '<a href="' + erpUrl + imgList[i] + '">' +
          '<span class="gc_download">下载</span> ' +
          '</a>'+
          '</span> ' +
          '</li>'
          target.append(html);
      }
    })
  }
  $(document).on('click', '.gc_preview', function () {        //  预览
    imgMagnify($(this).parent().prev().attr('src'));
  })
</script>
