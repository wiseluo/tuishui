@extends('admin.layouts.auth')

@section('content')
    <div class="container aside-xxl" style="margin-top:14%;">
        <section class="panel panel-default bg-white m-t-lg" style="margin-top:20px; border-radius:25px; border:none;">
            <header class="panel-heading text-center" style=" background-color:#0CF; border: none; height:60px; color:#FFF; font-size:18px; line-height:40px; border-radius:25px 25px 0 0">
                <strong>退税管理系统</strong>
            </header>
            <form class="panel-body wrapper-lg" method="POST" action="{{ route('admin.login') }}">
                {{ csrf_field() }}
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="control-label" style="width:15%; font-size:14px" for="username">用户名</label>
                    <input id="username" type="text" style="height:30px; border:none; border-bottom:solid 1px #d9d9d9; width:80%; padding-left:10px" name="username" value="{{ old('username') }}" required autofocus>

                    @if ($errors->has('username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="control-label" style="width:15%; font-size:14px" for="password">密码</label>
                    <input id="password" type="password" style="height:30px; border:none; border-bottom:solid 1px #d9d9d9; width:80%; padding-left:10px" name="password" required>

                    @if ($errors->has('password'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                    @endif
                </div>
                <div class="checkbox" style="line-height:20px; margin-top:20px">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> 记住密码
                    </label>
                </div>
                <div style="text-align:center">
                <button type="submit" class="btn btn-primary" style="width:150px; font-size:14px">登录</button>
                </div>
            </form>
        </section>
    </div>
@endsection


<style>
    html.bg-dark{ background:url(../../images/center/background.jpg); height:100%; width:100%}
	.container aside-xxl{ margin-top:300px;}
</style>
