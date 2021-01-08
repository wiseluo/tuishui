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
                        <th>报关单</th>
                        <th>报关发票</th>
                        <th>报关箱单</th>
                        <th>报关资料草单</th>
                        <th>报关资料合同</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <span class="text-info" data-upload="download">  <a href="{{URL::to('member/download/customs', [request()->route( 'id' )])}} " download>下载</a>  </span>
                        </td>
                        <td>
                            <span class="text-info" data-upload="download">  <a href="{{route('member/invoice', ['id' => request()->route('id')])}}" download>下载</a>  </span>
                        </td>
                        <td>
                              <span class="text-info" data-upload="download">  <a href="{{route('member/list', ['id' => request()->route('id')])}}" download>下载</a>  </span>
                        </td>
                        <td>
                            <span class="text-info" data-upload="download"><a href="{{route('member/customsSheet', ['id'=>request()->route('id')])}}" download>下载</a></span>
                        </td>
                        <td>
                            <span class="text-info" data-upload="download"><a href="{{route('member/customsContract', ['id'=>request()->route('id')])}}" download>下载</a></span>
                        </td>
                        <td>
                            <span class="text-info" data-upload="download"><a href="{{route('member/packageDownload', ['id'=>request()->route('id')])}}" download>打包下载</a></span>
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

  })
</script>