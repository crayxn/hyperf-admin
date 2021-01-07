@extends('common.full')
@section('head')
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <script>if (location.href.indexOf('#') > -1) location.replace(location.href.split('#')[0])</script>
    <style>
        body {
            height: 100%;
        }
        .login {
            font-family: "MicrosoftYaHei";
            background: url(https://crmimageoss.returnsaas.com/p_apm/bg.png) no-repeat;
            background-size: cover;
            position: relative;
            height: 900px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            justify-items: center;
            flex-direction: column;
        }

        .login .login_form {
            margin: 0 auto;
            width: 360px;
            height: 390px;
            background: rgba(255, 255, 255, 1);
            box-shadow: 1px 5px 32px 4px rgba(43, 42, 42, 0.43);
            border-radius: 15px;
            display: flex;
            justify-items: center;
            align-items: center;
            flex-direction: column;
        }

        .login .login_form .title {
            height: 18px;
            font-size: 18px;
            font-weight: bold;
            color: #4E83FF;
            margin: 30px auto 10px auto;
        }

        .login .login_form .form {
            margin: 40px auto;
            padding: 0 20px;
            display: flex;
            flex-direction: column;
            width: 260px;
        }
        .login .login_form .form >div{
            padding: 2px 20px 2px 20px;
            border: 1px solid #ddd;
            border-radius: 18px;
            margin: 0 0 20px 0;
        }

        .login .login_form .form > div > input {
            height: 32px;
            border: none;
            outline: none;
            color: #5d5d58;
            width: 80%;
            font-size: 15px;
            background: transparent;
        }
        .login .login_form .form .login_btn {
            width:100%;
            height:40px;
            background:linear-gradient(-125deg, #80a4fc, #316DFC);
            border-radius:27px;
            color: #fff;
            border-width:0;
        }

        .login .copyright {
            font-size: 14px;
            color: #fff;
            position: fixed;
            bottom: 30px;
            width: 100%;
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <div class="login">
        <div class="login_form">
            <div class="title">登陆</div>
            <form onsubmit="return false;" data-time="0.001" data-auto="true" method="post"
                  class="form content layui-form animated fadeInDown">
                @_token()
                <div class="user">
                    <input name="phone" type="text" autofocus="autofocus" autocomplete="off" placeholder="请输入手机号">
                </div>
                <div class="pwd">
                    <input name="password" type="password" placeholder="请输入密码">
                </div>
                <input type="submit" class="login_btn" value="登录">
            </form>
        </div>
    </div>
@endsection