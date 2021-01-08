<!--申报查看、新增-->
<style>
    .breadcrumb_flow{
        padding: 10px 5px 5px 10px;
        position: relative;
    }
    .breadcrumb_flow p{
        position: absolute;
        font-size: 14px;
        line-height: 20px;
        width: 44px;
        background: #fff;
        text-align: center;
        left: 50%;
        margin-left: -22px;
        top: 6px;
    }
    .breadcrumb_flow_chart{
        padding: 18px 5px 13px;
        border: 1px dashed #ccc;
        border-radius: 5px;
        text-align: center;
    }
    .flow_chart{
        display: inline-block;
        line-height: 26px;
        padding: 2px 6px;
        font-size: 12px;
        overflow: hidden;
        white-space: nowrap;
    }
    .flow_chart_start, .flow_chart_end{
        background: #FFB8B8;
        color: #fff !important;
        border: 1px solid #F99393;
        border-radius: 50%;
    }
    .glyphicon-arrow-right{

    }
    .flow_chart_con{
        background: #fff;
        color: #0895FD !important;
        border: 1px solid #5EA1E0;
        border-radius: 48%;
        max-width: 58px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .ap_pass{
        color: #fff;
        border-color: #7eb0e0;
        background-color: #add7ff;
    }
    .ap_current{
        color: #fff !important;
        background-color: #0895fd;
    }
</style>
<div class="fildetail">
    <div class="layui-row layui-col-space10" style="line-height:30px;margin:15px 0 6px">
        <div class="breadcrumb_flow">
            <p>流程</p>
                <div class="breadcrumb_flow_chart">
                <span class="flow_chart flow_chart_start">开始</span>
                <span class="flow_chart glyphicon glyphicon-arrow-right">&nbsp;</span>
                <div class="workFlowList" style="display:inline-block"></div>
                <span class="flow_chart flow_chart_end">结束</span>
            </div>
        </div>
    </div>
    <form class="form-horizontal" style="overflow: hidden;">
        <input type="hidden" name="status">
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="apply_at" class="col-sm-4 control-label necessary_front">申报日期</label>
                <div class="col-sm-8">
                    <input type="text" value="" id="applied_at" class="form-control" name="applied_at" readonly data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="apply_number" class="col-sm-4 control-label necessary_front">申报批次</label>
                <div class="col-sm-8" style="position: static">
                    <input type="text" value="" id="batch" name="batch" class="form-control" readonly data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">退回金额</label>
                <div class="col-sm-8">
                    <input type="text" name="amount" class="form-control amount" data-parsley-number-message="请输入最多两位小数" data-parsley-number   data-parsley-required value="" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">票数</label>
                <div class="col-sm-8">
                    <input type="text" name="invoice_quantity" class="form-control invoice_quantity" data-parsley-type="integer" data-parsley-required data-parsley-trigger="change" value="" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">退回日期</label>
                <div class="col-sm-8">
                    <input type="text" name="returned_at" id="endDate" readonly class="form-control" data-parsley-required data-parsley-trigger="change" value="" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label ">录入日期</label>
                <div class="col-sm-8">
                    <input type="text" readonly value="" class="form-control created_at" data-parsley-required data-parsley-trigger="change" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="register" class="col-sm-4 control-label">录入人员</label>
                <div class="col-sm-8">
                    <input type="text" id="register" value="" class="form-control" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-12 m-t-sm">
            <div class="form-group">
                <label class="col-sm-1 control-label" style="padding:7px 0;">函调</label>
                <div class="col-sm-11">
                    <textarea id="letter" class="form-control letter" rows="3" disabled></textarea>
                </div>
            </div>
        </div>
        <div class="col-sm-12 m-t-sm">
            <div class="form-group">
                <label class="h5 text-danger col-sm-12 control-label" style="text-align:left">发票信息</label>
            </div>
        </div>
        <div class="col-sm-12 m-t-sm">
            <div class="form-group">
                <div class="table-responsive panel" style="overflow-x:auto;margin:0 15px;">
                    <table class="table table-hover b-t b-light infoList" style="table-layout: fixed;">
                        <thead>
                        <tr class="info">
                            <th width="15%">订单号</th>
                            <th width="120">发票号</th>
                            <th width="100">开票时间</th>
                            <th width="120">开票金额</th>
                            <th width="110">收票时间</th>
                        </tr>
                        </thead>
                        <tbody class="ftbody">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>
