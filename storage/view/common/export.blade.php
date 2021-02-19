<script>
    function _export(url, count = 0, question = '') {
        if (count < 1) {
            $.msg.error('导出条数不能为空');
        }
        let confirm = $.msg.confirm(
            `将总共处理导出${count}条数据,预计${count > 10000 ? '超过' : '少于'}一分钟。请耐心等待。`
            , function () {
                layer.close(confirm);
                layer.open({
                    type: 2,
                    shade: 0,
                    offset: 'rb',
                    title: "<span class='layui-icon layui-icon-loading layui-icon layui-anim layui-anim-rotate layui-anim-loop'></span> &nbsp;&nbsp;数据导出",
                    content: `/tool/export?count=${count}&url=${url}`,
                    area: ['250px', '120px'],
                    scrollbar: false,
                    maxmin: false,
                    shadeClose: false,
                    closeBtn: 0
                });
            }
            , function () {
                //NO
                layer.close(confirm);
            }
            , question || '导出操作提醒', ['确定导出', '取消']
        )
    }
</script>