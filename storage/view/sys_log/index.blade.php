@extends('common.content')
@section('button')
    @auth("$controller/del")
        <a data-table='ta' data-confirm="确认批量删除日志?" data-rule='ids#{key}' data-url='{{$controller}}/del'
           class='layui-btn layui-btn-sm layui-btn-danger'>删除</a>
    @endauth
@endsection
@section('content')
    <div class="layui-col-lg12">
        <div class="layui-card overflow_auto">
            <div class="layui-card-body">
                <!-- endsearch -->
                @include('common.table', ['id' => 'ta', "cols" => [
                    ["field" => "action", "title" => "操作方法"],
                    ["field" => "sys_user_id", "title" => "操作用户", "templet" => "#user"],
                    ["field" => "created_at", "title" => "操作时间", "templet" => "#created_at"],
                ], '_token' => ""])
            </div>
        </div>
    </div>
@endsection
@section("script")
    <script type="text/html" id="created_at">
        @{{#
        let date = $.fn.formatDate(d.created_at);
        }}
        @{{ date }}
    </script>
    <script type="text/html" id="user">
        @{{ d.user.name }}
    </script>
    <script>
        window.form.render();
    </script>
@endsection