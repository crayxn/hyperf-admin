<div class="layui-card layui-bg-gray">
    @yield("style")
    @if(isset($title))
        <div class="layui-card-header notselect">
            <span class="layui-breadcrumb" style="visibility: visible;">
              <a id="breadcrumb" class="color-desc" href="/">Home</a><span lay-separator="">/</span>
              <a class="color-text"><cite>{{$title}}</cite></a>
            </span>
            <div class="pull-right">@yield("button")</div>
        </div>
    @endif
    <div class="layui-card-body">
        <div class="layui-row layui-col-space15">
            @yield("content")
        </div>
    </div>
    @yield("script")
</div>
<script>

    $(function () {
        @if(isset($title))
        //处理面包屑
        let str = "";
        let hash = location.hash;
        let hash_short = hash.match(/#(\S*)\?/)[1];
        let need_pop = !/back/.test(hash);
        let is_exit = false;
        let breadcrumb = JSON.parse(localStorage.getItem("breadcrumb") || '[]');
        if(need_pop) breadcrumb = [];
        breadcrumb.map(function (item,index) {
            is_exit = new RegExp(hash_short).test(item.url);
            if (need_pop || is_exit) {
                need_pop = true;
                breadcrumb.splice(index + 1,1);
            }else {
                str += '<span lay-separator="">/</span><a class="color-desc" data-open="' +
                    item.url +
                    '">' +
                    item.title +
                    '</a>'
            }
        });
        $("#breadcrumb").after(str);
        //插入
        if(!is_exit){
            breadcrumb.push({
                url: window.location.hash.replace("#", ""),
                title: "{{$title ?? ''}}"
            })
        }
        localStorage.setItem("breadcrumb",JSON.stringify(breadcrumb))
        @endif
    })

</script>