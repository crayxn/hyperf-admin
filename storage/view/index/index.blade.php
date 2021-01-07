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
    <link rel="stylesheet" href="/static/plugs/awesome/fonts.css?at={{$v}}">
    <link rel="stylesheet" href="/static/plugs/layui/css/layui.css?at={{$v}}">
    <link rel="stylesheet" href="/static/theme/css/console.css?at={{$v}}">
    @yield("head")
    <script>window.tapiRoot = '/';window.breadcrumb = []</script>
    <script src="/static/plugs/jquery/pace.min.js"></script>
</head>
<body class="layui-layout-body" style="background: #1f2d3d;">
<div class="layui-layout layui-layout-admin layui-layout-left-hide">
    <!-- 顶部菜单 开始 -->
    <div class="layui-header think-bg-blue notselect">
        <a href="/" class="layui-logo layui-elip">
            {{$app_name}} <sup>1.0</sup>
        </a>
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item" lay-unselect>
                <a class="text-center" data-target-menu-type>
                    <i class="layui-icon layui-icon-spread-left"></i>
                </a>
            </li>
            @foreach($menus as $one)
                <li class="layui-nav-item">
                    <a data-menu-node="m-{{$one['id']}}" data-open="{{$one['url']}}">
                        @if(!empty($one['icon'])) <span class='{{$one['icon']}} padding-right-5'></span> @endif
                        <span>{{$one['title'] ? $one['title'] : ""}}</span>
                    </a>
                </li>
            @endforeach
        </ul>
        <ul class="layui-nav layui-layout-right">
            <li lay-unselect class="layui-nav-item"><a data-reload><i class="layui-icon layui-icon-refresh-3"></i></a>
            </li>
            <li class="layui-nav-item">
                <dl class="layui-nav-child">
                    <dd lay-unselect><a data-modal="/sys_user/user"><i class="iconfont icon-account fa"></i> 基本资料</a></dd>
                    <dd lay-unselect><a data-modal="/sys_user/pwdSelf"><i class="iconfont icon-set fa"></i> 修改密码</a></dd>
                    <dd lay-unselect><a data-load="/sys_user/logout" data-confirm="确定要退出登录吗？"><i class="iconfont icon-skip fa"></i> 退出登录</a></dd>
                </dl>
                <a class="layui-elip">
                    <img alt="headimg" src="{{ $user['avatar'] ?? '/static/theme/img/headimg.png'}}">
                    <span>{{ $user['name'] }}</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- 顶部菜单 结束 -->
    <!-- 左则菜单 开始 -->
    <div class="layui-side layui-bg-black notselect">
        <div class="layui-side-scroll">
            @foreach($menus as $one)
                @if(!empty($one['sub']))
                    <ul class="layui-nav layui-nav-tree layui-hide" data-menu-layout="m-{{$one['id']}}">
                        @foreach($one['sub'] as $two)
                            @if(empty($two['sub']))
                                <li class="layui-nav-item">
                                    <a data-target-tips="{{$two['title']}}"
                                       data-menu-node="m-{{$one['id']."-".$two['id']}}"
                                       data-open="{{$two['url']}}">
                                        <span class='nav-icon {{$two['icon']?$two['icon']:"layui-icon layui-icon-link"}}'></span>
                                        <span class="nav-text">{{$two['title']?$two['title']:''}}</span>
                                    </a>
                                </li>
                            @else
                                <li class="layui-nav-item" data-submenu-layout='m-{{$one['id']."-".$two['id']}}'>
                                    <a data-target-tips="{{$two['title']}}" style="background:#1f2d3d">
                                        <span class='nav-icon {{ $two['icon'] ? $two['icon'] : "layui-icon layui-icon-triangle-d" }}'></span>
                                        <span class="nav-text">{{ $two['title']?$two['title']:''}}</span>
                                    </a>
                                    <dl class="layui-nav-child">
                                        @foreach($two['sub'] as $thr)
                                            <dd>
                                                <a data-target-tips="{{$thr['title']}}" data-open="{{$thr['url']}}"
                                                   data-menu-node="m-{{$one['id']."-".$two['id']."-".$thr['id']}}">
                                                    <span class='nav-icon {{$thr['icon']?$thr['icon']:"layui-icon layui-icon-link"}}'></span>
                                                    <span class="nav-text">{{$thr['title']?$thr['title']:""}}</span>
                                                </a>
                                            </dd>
                                        @endforeach
                                    </dl>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            @endforeach
        </div>
    </div>
    <!-- 左则菜单 结束 -->
    <!-- 主体内容 开始 -->
    <div class="layui-body layui-bg-gray">
        @yield("content")
    </div>
    <!-- 主体内容 结束 -->
</div>
<script src="/static/plugs/layui/layui.all.js"></script>
<script src="/static/plugs/require/require.js"></script>
<script src="/static/admin.js"></script>
@yield('script')
</body>
</html>
