<style>
    .ptbody input{
        border:none;
        text-align: center;
        background: inherit;
    }
    .parsley-errors-list{
        margin-top:3px;
    }
    .form-group{
        margin-bottom: 15px !important
    }
</style>
<!--联系人修改，新增-->
<div class="linkdetail panel-body">
    <form class="form-horizontal" style="overflow: hidden;">
        <div class="form-group">
            <label class="col-sm-3 control-label necessary_front">联系人名称</label>
            <div class="col-sm-8">
                <input type="text" id="peopleName" class="form-control" name="name" value="{{ isset($link) ? $link->name : '' }}" placeholder="输入联系人名称" data-parsley-required data-parsley-trigger="change" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label necessary_front">联系电话</label>
            <div class="col-sm-8">
                <input type="text" id="phone" class="form-control" name="phone" value="{{ isset($link) ? $link->phone : '' }}" placeholder="输入正确的手机号" data-parsley-required data-parsley-trigger="change" />
            </div>
        </div>
    </form>
</div>
