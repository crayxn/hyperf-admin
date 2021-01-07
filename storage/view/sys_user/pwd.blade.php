<form class="layui-form padding-20 padding-left-0" action="{{$url}}" data-auto="true" method="post" autocomplete="off">
    @_token()
    <div class="layui-form-item">
        <label class="layui-form-label">请输入新密码</label>
        <div class="layui-input-block">
            <input class="layui-input" type="password" placeholder="请输入密码" name="password">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">请重复新密码</label>
        <div class="layui-input-block">
            <input class="layui-input" type="password" placeholder="请重复新密码" name="repassword">
        </div>
    </div>
    <div class="layui-form-item text-center">
        @isset($id)
            <input type="hidden" name="id" value="{{ $id }}">
        @endisset()
        <button class="layui-btn" type='submit'>保存数据</button>
        <button class="layui-btn layui-btn-danger" type='button' data-confirm="确定要取消编辑吗？" data-close>取消编辑</button>
    </div>
</form>