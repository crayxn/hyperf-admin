<!-- table -->
<table id="{{$id}}" lay-filter="s_table_{{$id}}"></table>
<!-- endtable -->
<!-- script -->
<script>
     _token = "{{$_token}}";
    layui.use('table', function () {
        let table = window.table = layui.table;
        //第一个实例
        let s_table = table.render({
            elem: '#{{$id}}'
            , url: window.location.hash.replace("#", "/") //数据接口
            , page: {{$page ?? true}} //开启分页
            , cellMinWidth: {{ $cellMinWidth ?? 60 }}
            , cols: [[{type: 'checkbox'},
                    @foreach( $cols as $key=>$item )
                {
                    field: "{{$item['field']}}", title: "{{$item['title']}}",
                    hide: "{{$item['hide'] ?? false }}",
                    sort: "{{$item['sort'] ?? false }}",
                    templet: "{{$item['templet'] ?? false}}",
                    @isset($item['fixed'])fixed: "{{ $item['fixed']}}",@endisset
                    @isset($item['edit'])edit: "{{ $item['edit']}}",@endisset
                },
                @endforeach
            ]],
            parseData: function (res) {
                return {
                    "code": res.code, //解析接口状态
                    "msg": res.msg, //解析提示文本
                    "count": res.data.count, //解析数据长度
                    "data": res.data.list //解析数据列表
                };
            }
        });

        //监听排序事件
        table.on('sort(s_table_{{$id}})', function (obj) {
            s_table.reload({
                initSort: obj
                , where: {
                    field: obj.field
                    , order: obj.type
                }, page: {
                    curr: 1
                }
            });
        });

    });
</script>

<!-- endsecript -->