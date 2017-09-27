@extends('layouts.login')

@section('content')
    <form class="login-form" action="{{url('admin/login')}}" method="post">
        {!! csrf_field() !!}
        <h3 class="form-title">管理后台</h3>
        @if (isset($errors) && count($errors) > 0 )
            <div class="alert alert-danger">
                <button class="close" data-close="alert"></button>
                @foreach($errors->all() as $error)
                    <span class="help-block"><strong>{{ $error }}</strong></span>
                @endforeach
            </div>
        @endif
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">{{trans('auth.email')}}</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="text" placeholder="email" name="email" value="{{old('email')}}" /> </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">{{trans('auth.password')}}</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="password" placeholder="Password" name="password" />
        </div>
        {{--<div class="form-group">--}}
            {{--<label class="control-label visible-ie8 visible-ie9">{{trans('auth.captcha')}}</label>--}}
            {{--<div class="input-group">--}}
                {{--<input id="newpassword" class="form-control" type="text" name="captcha" placeholder="captcha">--}}
                {{--<span class="input-group-btn">--}}
                {{--<img style="cursor: pointer;" src="{{captcha_src("flat")}}" onclick="this.src='{{captcha_src("flat")}}' + Math.random()">--}}
            {{--</span>--}}
            {{--</div>--}}
        {{--</div>--}}
        <div class="form-actions">
            <button type="submit" class="btn green uppercase">登录</button>

        </div>
    </form>
@endsection
