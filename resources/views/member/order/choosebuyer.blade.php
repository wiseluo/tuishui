<!--分配业务员-->
<div class="chooseCus panel-body">
    <div class="form-group" style="margin-left: 25px">
        <div class=" " style="width: 200px;">
                <input  style="width: 200px;float: left" type="text" class="form-control" id="keyword" name="keyword" placeholder="输入姓名">
        </div>
        <button onclick="send()" class="btn btn-success m-l-md">查询</button>
    </div>
    <div class="table-responsive panel" style="overflow-x:auto;height: 450px;">
        <table class="table table-hover b-t b-light" style="table-layout: fixed;">
            <thead>
            <tr class="info">
                <th width="50%">员工账号22222</th>
                <th width="50%">姓名</th>
            </tr>
            </thead>
            <tbody class="buyerTbody">

            </tbody>
        </table>
    </div>
</div>
<script>
    $(function () {
        send();
        $(document).on('click','.buyerTbody tr',function () {
          $(this).addClass('bg-active').siblings().removeClass('bg-active');
        })
    })
    function send() {
//      $('.buyerTbody').html('');
      var n = $('#keyword').val()
      var html = '';
      $.post('/member/choosebuyer',{name: n},function (res) {
        $.each(JSON.parse(res).data,function (a,b) {
          html += '<tr data-id="' + b.u_id + '">' +
            '<td class="u_name">' + b.u_name + '</td>' +
            '<td class="u_truename">' + b.u_truename + '</td>' +
            '</tr>';
        })
        $('.buyerTbody').html(html);
      })
    }
</script>