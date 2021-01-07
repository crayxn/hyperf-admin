<!DOCTYPE html>
<html lang="zh">
<head>
    <title>
        @if( !empty($title) ){{$title}}.@endif{{ $app_name }}
    </title>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=0.4">
    <link rel="shortcut icon" href="">
    <link rel="stylesheet" href="/static/plugs/awesome/fonts.css?at={{ $v??date("md") }}">
    <link rel="stylesheet" href="/static/plugs/layui/css/layui.css?at={{$v??date("md")}}">
    <link rel="stylesheet" href="/static/theme/css/console.css?at={{$v??date("md")}}">
    @yield("head")
    <script>window.tapiRoot = '/'</script>
    <script src="/static/plugs/jquery/pace.min.js"></script>
</head>
<body class="layui-layout-body">
@yield("content")
<script src="/static/plugs/layui/layui.all.js"></script>
<script src="/static/plugs/require/require.js"></script>
<script src="/static/admin.js"></script>
@yield('script')
</body>
</html>
