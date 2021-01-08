$(document).on('click', '#form_submit', function () {
  var username = $("#username").val();
  if (username == '') {
    $("#username").focus();
    layer.msg('请输入用户名')
    return false;
  }
  if ($("#name").val() == '') {
    $("#name").focus();
    layer.msg('请输入昵称')
    return false;
  }

  if ($("#password").val() == '') {
    $("#password").focus();
    layer.msg('请输入密码')
    return false;
  }

  if ($("#repassword").val() == '') {
    $("#repassword").focus();
    layer.msg('请再次输入密码')
    return false;
  }

  if ($('#password_confirmation').val() !== $('#password').val()) {
    layer.msg('两次密码输入不一致')
    return false
  }


  // if ($("#phone").val() == '') {
  //   $("#phone").focus();
  //   layer.msg('请输入电话号码')
  //   return false;
  // }

  // var myreg = /^(((13[0-9]{1})|(14[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
  // if (!myreg.test($("#phone").val())) {
  //   $("#phone").focus();
  //   layer.msg('请输入有效的手机号码！');
  //   return false;
  // }


  if ($('input#agree').is(':checked') == true) {
    var data = $('#register_form').serialize()
    $.post('/api/register',data,function(res){
      layer.msg(res.msg)
      if(res.code == 200){
        window.location.href="/";
      }
    })
  } else {
    layer.msg('请先同意条款')
  }
})

$(document).on('click','#update_submit',function(){
    if($('#password').val().trim().length < 6) {
        layer.msg('旧密码不能小于6位')
        return false
    }
    if($('#newpassword').val().trim().length < 6){
        layer.msg('新密码不能小于6位')
        return false
    }
    if ($('#newpassword').val() === $('#password').val()) {
      layer.msg('两次密码输入一致')
      return false
  } else {
      let data = $.param({"token":$.cookie('token')}) + "&" + $('#password_form').serialize()
      $.ajax({
          type: 'post',
          url: '/api/password/reset',
          data: data,
          success: function(res){
              if(res.code == 200){
                  layer.msg(res.msg)
                  window.top.location.href = '/'
              } else {
                  layer.msg(res.msg)
              }
          }
      })
  }
})


$(document).on('click', '.item_book', function () {
  layer.open({
    title: '条款',
    area: ['800px', '600px'],
    content: `<div style="padding:10px;"><p>(a) 违反宪法确定的基本原则的；
      (b) 危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；
      (c) 损害国家荣誉和利益的；
      (d) 煽动民族仇恨、民族歧视，破坏民族团结的；
      (e) 破坏国家宗教政策，宣扬邪教和封建迷信的；
      (f) 散布谣言，扰乱社会秩序，破坏社会稳定的；
      (g) 散布淫秽、色情、赌博、暴力、恐怖或者教唆犯罪的；
      (h) 侮辱或者诽谤他人，侵害他人合法权益的；
      (i) 煽动非法集会、结社、游行、示威、聚众扰乱社会秩序的；
      (j) 以非法民间组织名义活动的；
      (k) 含有法律、行政法规禁止的其他内容的。
      2）用户在网易的网页上发布信息或者利用网易的服务时还必须符合其他有关国家和地区的法律规定以及国际法的有关规定。用户需遵守法律法规的规定使用网易微博客服务。
      （3）用户不得利用网易的服务从事以下活动：

      (a) 未经允许，进入计算机信息网络或者使用计算机信息网络资源的；

      (b) 未经允许，对计算机信息网络功能进行删除、修改或者增加的；

      (c) 未经允许，对进入计算机信息网络中存储、处理或者传输的数据和应用程序进行删除、修改或者增加的；

      (d) 故意制作、传播计算机病毒等破坏性程序的；

      (e) 其他危害计算机信息网络安全的行为。

      （4）用户不得以任何方式干扰网易的服务。

      （5）用户不得滥用网易服务，包括但不限于：利用网易公司提供的邮箱服务发送垃圾邮件，利用网易服务进行侵害他人知识产权或者合法利益的其他行为。

      （6）用户应遵守网易的所有其他规定和程序。</p></div>`
  })
})
