<!--品牌弹窗 :详情，修改，新增-->
<div class="branddetail">
    <form class="form-horizontal brandName" style="overflow: hidden;">
        <input type="hidden" name="status" class="status">
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="brandName" class="col-sm-4 control-label necessary_front">品牌名称</label>
                <div class="col-sm-8">
                    <input type="text" id="brandName" name="name" class="form-control" data-parsley-required
                           data-parsley-trigger="change" value="{{ isset($brand) ? $brand->name : ''}}" {{ $disabled }} placeholder="输入品牌名称"/>
                </div>
            </div>
        </div>
        @include('member.common.dataselect', ['label'=>'品牌类型', 'var'=>'type', 'obj'=>isset($brand) ? $brand : ''])
        @include('member.common.dataselect', ['label'=>'产品分类', 'var'=>'classify', 'obj'=>isset($brand) ? $brand : ''])

        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="logoPic" class="col-sm-4 control-label necessary_front">品牌logo图</label>
                <div class="col-sm-5">
                    <input type="button" id="logoPic" class="text-center form-control btn-s btn-primary"
                           value="品牌logo图" data-upload="image" data-input="[name=logo_img]"/>
                    <input type="hidden" name="logo_img" value="{{ isset($brand) ? $brand->logo_img : ''}}" data-parsley-required
                           data-parsley-trigger="change">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="authPic" class="col-sm-4 control-label">授权书</label>
                <div class="col-sm-5">
                    <input type="button" id="authPic" class="text-center form-control btn-s btn-primary"
                           value="授权书" data-upload="image" data-input="[name=auth_img]"/>
                    <input type="hidden" name="auth_img" value="{{ isset($brand) ? $brand->auth_img : ''}}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="proNumber" class="col-sm-4 control-label necessary_front">联系人名字</label>
                <div class="col-sm-8">
                    <input type="text" id="proNumber" name="number" class="form-control"
                           value="{{ isset($brand) ? $brand->link->name : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="proNumber" class="col-sm-4 control-label">联系人电话</label>
                <div class="col-sm-8">
                    <input type="text" id="proNumber" name="number" class="form-control"
                           value="{{ isset($brand) ? $brand->link->phone : ''}}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        @include('member.common.approve', ['obj'=>isset($brand) ? $brand : ''])
    </form>
</div>
