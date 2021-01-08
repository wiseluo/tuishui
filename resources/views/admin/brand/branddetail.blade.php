<!--品牌弹窗 :详情，修改，新增-->
<div class="branddetail m-t">
    <form class="form-horizontal brandName" style="overflow: hidden;">
        <input type="hidden" name="status" class="status">
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="brandName" class="col-sm-4 control-label necessary_front">品牌名称</label>
                <div class="col-sm-8">
                    <input type="text" id="brandName" name="name" class="form-control" data-parsley-required
                           data-parsley-trigger="change" value="{{ isset($brand) ? $brand->name : ''}}" {{ $disabled }} placeholder="输入品牌名称"/>
                </div>
            </div>
        </div>
        @include('admin.common.dataselect', ['label'=>'品牌类型', 'var'=>'type', 'obj'=>isset($brand) ? $brand : ''])
        @include('admin.common.dataselect', ['label'=>'产品分类', 'var'=>'classify', 'obj'=>isset($brand) ? $brand : ''])

        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="logoPic" class="col-sm-4 control-label necessary_front">品牌logo图</label>
                <div class="col-sm-5">
                    <input type="button" id="logoPic" class="text-center form-control btn-s btn-primary" data-parsley-required
                           data-parsley-trigger="change" value="品牌logo图" data-upload="image" data-input="[name=logo_img]"/>
                    <input type="hidden" name="logo_img" value="{{ isset($brand) ? $brand->logo_img : ''}}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="authPic" class="col-sm-4 control-label">授权书</label>
                <div class="col-sm-5">
                    <input type="button" id="authPic" class="text-center form-control btn-s btn-primary" value="授权书" data-upload="image" data-input="[name=auth_img]"/>
                    <input type="hidden" name="auth_img" value="{{ isset($brand) ? $brand->auth_img : ''}}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="linkName" class="col-sm-4 control-label necessary_front">联系人名字</label>
                <div class="col-sm-8">
                    <input type="text" id="linkName" class="form-control choose_name" value="{{ isset($brand->link->name) ? $brand->link->name : ''}}" data-parsley-required
                           data-parsley-trigger="change" {{ $disabled }} readonly/>
                    <input type="hidden" id="linkId" name="link_id" class="form-control" value="{{ isset($brand->link->id) ? $brand->link->id : ''}}" {{ $disabled }} />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="linkPhone" class="col-sm-4 control-label">联系人电话</label>
                <div class="col-sm-8">
                    <input type="text" id="linkPhone" class="form-control" value="{{ isset($brand->link->phone) ? $brand->link->phone : ''}}" readonly />
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        @include('admin.common.approve', ['obj'=>isset($brand) ? $brand : ''])
    </form>
</div>
<script>
    $('.choose_name').on('click',function () {
        $.ajax({
            url: '/admin/link/linklist',
            data: {
                action: 'html'
            },
            success: function (str) {
                if(str.code == 403){
                    layer.msg(str.msg)
                    return false
                }
                layer.open({
                    type: 1,
                    title: '联系人选择',
                    content: str,
                    area:'600px',
                    btn: ['确定'],
                    yes: function (i) {
                        let id = $(document).find('.bg-active').data('id')
                            name = $(document).find('.bg-active .name').text()
                            phone = $(document).find('.bg-active .phone').text()
                        $('#linkName').val(name)
                        $('#linkId').val(id)
                        $('#linkPhone').val(phone)
                        layer.close(i)
                    }
                })
            }
        })
    })
</script>
