@extends('common.content')
@section('button')
@endsection
@section('content')
    <div class="layui-col-lg12">
        <div class="layui-card overflow_auto">
            <div class="layui-card-header">
                @auth("$controller/add")
                    <a data-open="{{$controller}}/add?back=1" data-title="添加权限"
                       class='layui-btn-success layui-btn layui-btn-sm '>添加</a>
                @endauth
                @auth("$controller/del")
                    <a data-table='ta' data-update data-field='delete' data-action='{{$controller}}/del'
                       class='layui-btn layui-btn-sm layui-btn-danger'>删除</a>
                @endauth
            </div>
            <div class="layui-card-body">
                <!-- endsearch -->
                @include('common.table', ['id' => 'ta', "cols" => [
                    ["field" => "name", "title" => "权限名称"],
                    ["field" => "desc", "title" => "权限说明"],
                    ["field" => "updated_at", "title" => "更新时间", "templet" => "#updated_at"],
                    ["field" => "created_at", "title" => "添加时间", "templet" => "#created_at"],
                    ["field" => "id", "title" => "操作", "templet" => "#op"],
                ], '_token' => $param['_token']])
            </div>
        </div>
    </div>
@endsection
@section("script")
    <script type="text/html" id="updated_at">
        @{{#
        let date = $.fn.formatDate(d.updated_at);
        }}
        @{{ date }}
    </script>
    <script type="text/html" id="created_at">
        @{{#
        let date = $.fn.formatDate(d.created_at);
        }}
        @{{ date }}
    </script>
    <script type="text/html" id="op">
        @auth("$controller/edit")

        @endauth
        <a class="layui-btn layui-btn-sm" data-open="{{$controller}}/edit?id=@{{d.id}}&back=1">编辑</a>
    </script>
    <script>
        window.form.render();
    </script>
@endsection