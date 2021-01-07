@extends('common.content')
@section('button')
@endsection
@section('content')
    <div class="layui-col-lg12">
        <div class="layui-card overflow_auto">
            <div class="layui-card-header">
                @auth("$controller/add")
                    <a data-modal="/{{$controller}}/add" data-title="添加用户"
                       class='layui-btn-success layui-btn layui-btn-sm '>添加</a>
                @endauth
                @auth("$controller/forbid")
                    <a data-table='ta' data-rule='id#{key}' data-url='{{$controller}}/forbid'
                       class='layui-btn layui-btn-sm layui-btn-warm'>禁用</a>
                @endauth
                @auth("$controller/resume")
                    <a data-table='ta' data-rule='id#{key}' data-url='{{$controller}}/resume'
                       class='layui-btn layui-btn-sm layui-btn-normal'>启用</a>
                @endauth
                @auth("$controller/del")
                    <a data-table='ta' data-rule='id#{key}' data-url='{{$controller}}/del'
                       class='layui-btn layui-btn-sm layui-btn-danger'>删除</a>
                @endauth

            </div>
            <div class="layui-card-body">
                <!-- search -->
                <form class="layui-form layui-form-pane form-search" action="{{$controller}}/index"
                      onsubmit="return false" method="get">

                    <div class="layui-form-item layui-inline">
                        <label class="layui-form-label">用户账号</label>
                        <div class="layui-input-inline">
                            <input name="name" value="{{$param['name'] ?? ''}}"
                                   placeholder="请输入用户名"
                                   class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item layui-inline">
                        <label class="layui-form-label">联系手机</label>
                        <div class="layui-input-inline">
                            <input name="phone" value="{{$param['phone'] ?? ''}}"
                                   placeholder="请输入联系手机"
                                   class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item layui-inline">
                        <button class="layui-btn layui-btn-primary"><i class="layui-icon">&#xe615;</i> 搜 索</button>
                    </div>

                </form>
                <!-- endsearch -->
                @include('common.table', ['id' => 'ta', 'cols' => [
                    ["field" => "name", "title" => "用户名称"],
                    ["field" => "phone", "title" => "手机号码", "sort" => true],
                    ["field" => "status", "title" => "当前状态","templet" => "#status"],
                    ["field" => "updated_at", "title" => "更新时间", "templet" => "#updated_at"],
                    ["field" => "status", "title" => "操作", "templet" => "#op"],
                ], '_token' => $param['_token']])
            </div>
        </div>
    </div>
@endsection
@section("script")
    <script type="text/html" id="status">@{{ d.status == 1?'<span class="color-blue">正常':'<span class="color-red">禁用' }}</span></script>
    <script type="text/html" id="updated_at">@{{# let date = $.fn.formatDate(d.updated_at);}} @{{ date }}</script>
    <script type="text/html" id="op">
        @auth("$controller/edit")
        <a class="layui-btn layui-btn-sm" data-title="编辑" data-modal="{{$controller}}/edit?id=@{{ d.id }}">编辑</a>
        @endauth
        @auth("$controller/pwd")
        <a class="layui-btn layui-btn-sm" data-title="修改密码" data-modal="{{$controller}}/pwd?id=@{{ d.id }}">修改密码</a>
        @endauth
    </script>
    <script>
        window.form.render();
    </script>
@endsection