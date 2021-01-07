@extends('common.content')
@section('button')
@endsection
@section('content')
    <div class="layui-col-lg12">
        <div class="layui-card overflow_auto">
            <div class="layui-card-body">
                <form class="layui-form padding-20 padding-left-0" action="{{$url}}" data-auto="true" method="post" autocomplete="off">
                    @_token()
                    <div class="layui-form-item">
                        <label class="layui-form-label">权限名称</label>
                        <div class="layui-input-block">
                            <input class="layui-input" placeholder="请输入权限名称" name="name" value="{{ $vo['name'] ?? "" }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">权限选择</label>
                        <div class="layui-input-block">
                            <div id="node"></div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">权限描述</label>
                        <div class="layui-input-block">
                            <textarea placeholder="请输入描述" class="layui-textarea" name="desc">{{ $vo['desc']??'' }}</textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-block">
                            @isset($vo['id'])
                                <input type="hidden" name="id" value="{{ $vo['id'] }}">
                            @endisset()
                            <button class="layui-btn" type='submit'>保存数据</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section("script")
    <script type="application/javascript">
        require(["xm-select"],function (e) {
            xmSelect.render({
                el: '#node',
                name: 'node_ids',
                theme: {
                    color: '#409EFF',
                },
                autoRow: true,
                model: {
                    type: 'relative',
                    label: {
                        type: 'block',
                        block: {
                            //最大显示数量, 0:不限制
                            showCount: 1,
                            //是否显示删除图标
                            showIcon: true,
                        }
                    }
                },
                tree: {
                    show: true,
                    showFolderIcon: true,
                    showLine: true,
                    indent: 20,
                    expandedKeys: [],
                },
                toolbar: {
                    show: true,
                    list: ['ALL', 'REVERSE', 'CLEAR']
                },
                height: 'auto',
                data: function () {
                    return JSON.parse('{!! json_encode($node) !!}');
                }
            })
        })
    </script>
@endsection
