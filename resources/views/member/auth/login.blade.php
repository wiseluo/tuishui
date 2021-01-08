@extends('member.layouts.auth')

@section('content')
    <div class="container aside-xxl">
        <a class="navbar-brand block" href="{{ url('/member') }}">{{ config('app.name', '退税') }}</a>
        <section class="panel panel-default bg-white m-t-lg">
            <header class="panel-heading text-center">
                <strong>Sign in</strong>
            </header>
            <form class="panel-body wrapper-lg" method="POST" action="{{ route('member.login') }}">
                {{ csrf_field() }}
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="control-label" for="username">用户名</label>
                    <input id="username" type="text" class="form-control input-lg" name="username" value="{{ old('username') }}" required autofocus>

                    @if ($errors->has('username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="control-label" for="password">密码</label>
                    <input id="password" type="password" class="form-control input-lg" name="password" required>

                    @if ($errors->has('password'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                    @endif
                </div>
                <div class="checkbox" style="line-height:20px;">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> 记住密码
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">登录</button>
            </form>
        </section>
    </div>
@endsection



