<style>
    tbody td span:not(.text-muted) {
        cursor: pointer;
    }

    input[type=file] {
        display: none;
    }
</style>

<div class="fildetail">
    <form class="form-horizontal" style="overflow: hidden;">
        <div class="form-group">
            <div class="table-responsive panel" style="overflow-x:auto;margin:0 15px;">
                <table class="table table-hover b-t b-light" style="table-layout: fixed;">
                    <thead>
                    <tr class="info">
                        <th>外销合同</th>
                        <th>报关预录单</th>
                        <th>报关单</th>
                        <th>放行书</th>
                        <th>提单</th>
                        <th>商业发票</th>
                        <th>装箱单</th>
                        <th>运费发票</th>
                        <th>清单</th>
                        <th>购销合同</th>
                        <th>第三方委托付款协议</th>
                        <th>其他资料</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <!-- <span class="text-danger" data-download="[name='generator']" data-filename="合同">下载 |</span>
                            <span class="text-danger preview">预览 |</span>
                            <span class="text-info" data-upload="file">上传</span>
                            <input type="file" class="upload_file" data-input="[name='generator']">
                            <input type="hidden" class="fileReader" name="generator" value="{{ $clearance->generator or '' }}"> -->
                            <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=generator]">上传</a>
                            <input type="hidden" class="fileReader" name="generator" value="{{ $clearance->generator or '' }}">
                        </td>

                        <td>
                            <!-- <span class="text-danger" data-download="[name='prerecord']" data-filename="报关预录单">下载 |</span>
                            <span class="text-danger preview">预览 |</span>
                            <span class="text-info" data-upload="file">上传</span>
                            <input type="file" class="upload_file" data-input="[name='prerecord']">
                            <input type="hidden" class="fileReader" name="prerecord" value="{{ $clearance->prerecord or '' }}"> -->
                            <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=prerecord]">上传</a>
                            <input type="hidden" class="fileReader" name="prerecord" value="{{ $clearance->prerecord or '' }}">
                        </td>

                        <td>
                            <!-- <span class="text-danger" data-download="[name='declare']" data-filename="报关单">下载 |</span>
                            <span class="text-danger preview">预览 |</span>
                            <span class="text-info" data-upload="file">上传</span>
                            <input type="file" class="upload_file" data-input="[name='declare']">
                            <input type="hidden" class="fileReader" name="declare" value="{{ $clearance->declare or '' }}"> -->
                            <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=declare]">上传</a>
                            <input type="hidden" class="fileReader" name="declare" value="{{ $clearance->declare or '' }}">
                        </td>

                        <td>
                            <!-- <span class="text-danger" data-download="[name='release']" data-filename="放行书">下载 |</span>
                            <span class="text-danger preview">预览 |</span>
                            <span class="text-info" data-upload="file">上传</span>
                            <input type="file" class="upload_file" data-input="[name='release']">
                            <input type="hidden" class="fileReader" name="release" value="{{ $clearance->release or '' }}"> -->
                            <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=release]">上传</a>
                            <input type="hidden" class="fileReader" name="release" value="{{ $clearance->release or '' }}">
                        </td>

                        <td>
                            <!-- <span class="text-danger" data-download="[name='lading']" data-filename="提单">下载 |</span>
                            <span class="text-danger preview">预览 |</span>
                            <span class="text-info" data-upload="file">上传</span>
                            <input type="file" class="upload_file" data-input="[name='lading']">
                            <input type="hidden" class="fileReader" name="lading" value="{{ $clearance->lading or '' }}"> -->
                            <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=lading]">上传</a>
                            <input type="hidden" class="fileReader" name="lading" value="{{ $clearance->lading or '' }}">
                        </td>
                        <td>
                            <!-- <span class="text-danger" data-download="[name='commercial_invoice']" data-filename="商业发票">下载 |</span>
                            <span class="text-danger preview">预览 |</span>
                            <span class="text-info" data-upload="file">上传</span>
                            <input type="file" class="upload_file" data-input="[name='commercial_invoice']">
                            <input type="hidden" class="fileReader" name="commercial_invoice" value="{{ $clearance->commercial_invoice or '' }}"> -->
                            <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=commercial_invoice]">上传</a>
                            <input type="hidden" class="fileReader" name="commercial_invoice" value="{{ $clearance->commercial_invoice or '' }}">
                        </td>
                        <td>
                            <!-- <span class="text-danger" data-download="[name='packing_List']" data-filename="装箱单">下载 |</span>
                            <span class="text-danger preview">预览 |</span>
                            <span class="text-info" data-upload="file">上传</span>
                            <input type="file" class="upload_file" data-input="[name='packing_List']">
                            <input type="hidden" class="fileReader" name="packing_List" value="{{ $clearance->packing_List or '' }}"> -->
                            <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=packing_List]">上传</a>
                            <input type="hidden" class="fileReader" name="packing_List" value="{{ $clearance->packing_List or '' }}">
                        </td>
                        <td>
                            <!-- <span class="text-danger" data-download="[name='transport']" data-filename="运费发票">下载 |</span>
                            <span class="text-danger preview">预览 |</span>
                            <span class="text-info" data-upload="file">上传</span>
                            <input type="file" class="upload_file" data-input="[name='transport']">
                            <input type="hidden" class="fileReader" name="transport" value="{{ $clearance->transport or '' }}"> -->
                            <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=transport]">上传</a>
                            <input type="hidden" class="fileReader" name="transport" value="{{ $clearance->transport or '' }}">
                        </td>
                        <td>
                            <!-- <span class="text-danger" data-download="[name='inventory']" data-filename="清单">下载 |</span>
                            <span class="text-danger preview">预览 |</span>
                            <span class="text-info" data-upload="file">上传</span>
                            <input type="file" class="upload_file" data-input="[name='inventory']">
                            <input type="hidden" class="fileReader" name="inventory" value="{{ $clearance->inventory or '' }}"> -->
                            <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=inventory]">上传</a>
                            <input type="hidden" class="fileReader" name="inventory" value="{{ $clearance->inventory or '' }}">
                        </td>
                        <td>
                            <!-- <span class="text-danger" data-download="[name='purchase_sale_contract']" data-filename="购销合同">下载 |</span>
                            <span class="text-danger preview">预览 |</span>
                            <span class="text-info" data-upload="file">上传</span>
                            <input type="file" class="upload_file" data-input="[name='purchase_sale_contract']">
                            <input type="hidden" class="fileReader" name="purchase_sale_contract" value="{{ $clearance->purchase_sale_contract or '' }}"> -->
                            <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=purchase_sale_contract]">上传</a>
                            <input type="hidden" class="fileReader" name="purchase_sale_contract" value="{{ $clearance->purchase_sale_contract or '' }}">
                        </td>
                        <td>
                            <!-- <span class="text-danger" data-download="[name='purchase_sale_contract']" data-filename="第三方委托付款协议">下载 |</span>
                            <span class="text-danger preview">预览 |</span>
                            <span class="text-info" data-upload="file">上传</span>
                            <input type="file" class="upload_file" data-input="[name='payment_agreement']">
                            <input type="hidden" class="fileReader" name="payment_agreement" value="{{ $clearance->payment_agreement or '' }}"> -->
                            <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=payment_agreement]">上传</a>
                            <input type="hidden" class="fileReader" name="payment_agreement" value="{{ $clearance->payment_agreement or '' }}">
                        </td>
                        <td>
                            <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=other_info]">上传</a>
                            <input type="hidden" class="fileReader" name="other_info" value="{{ $clearance->other_info or '' }}">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>
<script>
  $(function () {
//      改变操作提示
        $('.fileReader').each(function () {
          if (!$(this).val()) {
            // $(this).parent().find('[data-download]').hide();
            // $(this).parent().find('.preview').hide()
          } else {
            // $(this).parent().find('[data-upload]').html('重新上传');
            // $(this).parent().find('.preview').show()
            $(this).parent().find('a').html('已有文件上传');
          }
        }).change(function () {
          // $(this).parent().find('[data-download]').show();
          // $(this).parent().find('.preview').show()
          // $(this).parent().find('[data-upload]').html('重新上传');
          $(this).parent().find('a').html('已有文件上传');
        })
  })
</script>
