<form class="layui-form layui-card" action="{{$url}}" data-auto="true" method="post" autocomplete="off">
    @_token()
    <div class="layui-card-body padding-left-40">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs2 text-center">
                <input type="hidden" name="avatar" value="{{ $user['avatar'] ?? '' }}">
                <script>$('[name=avatar]').uploadOneImage()</script>
            </div>
            <div class="layui-col-xs5">
                <label class="block relative">
                    <span class="font-w7">账户名称</span>
                    <input name="name" value="{{ $user['name'] ?? '' }}" required placeholder="请输入账户名称" class="layui-input">
                    <span class="help-block">用户显示的账号别名，请尽量保持不要重复</span>
                </label>
            </div>
            <div class="layui-col-xs5">
                <label class="block relative">
                    <span class="font-w7">手机号码</span>
                    <input name="phone" value="{{ $user['phone'] ?? '' }}" required placeholder="请输入用户手机号码" class="layui-input">
                    <span class="help-block">用户显示的账号别名，请尽量保持不要重复</span>
                </label>
            </div>
        </div>
        @if(!empty($authorizes))
        <div class="hr-line-dashed margin-top-10 margin-bottom-10"></div>
        <div class="layui-form-item">
            <span class="font-w7">访问权限</span>
            <span class="color-desc margin-left-5">Authorize</span>
            <div class="layui-textarea">
                @if(empty($authorizes))
                <span class="color-desc">未配置权限</span>
                @else
                @foreach( $authorizes as $id => $name)
                <label class="think-checkbox layui-unselect">
                    @if(isset($user['authorize']) && in_array($id, $user['authorize']))
                    <input type="checkbox" checked name="authorize[]" value="{{ $id }}" lay-ignore> {{ $name }}
                    @else
                    <input type="checkbox" name="authorize[]" value="{{ $id }}" lay-ignore> {{ $name }}
                    @endif
                </label>
                @endforeach
                @endif
            </div>
        </div>
        @endif
        <div class="hr-line-dashed margin-top-10 margin-bottom-10"></div>
        <label class="layui-form-item block relative">
            <span class="font-w7">备注</span>
            <textarea placeholder="请输入用户描述" class="layui-textarea" name="remark">{{ $user['remark']??'' }}</textarea>
        </label>
        <div class="hr-line-dashed"></div>
        <div class="layui-form-item text-center">
            @isset($user['id'])
                <input type="hidden" value="{{ $user['id'] }}" name="id">
            @endisset()
            <button class="layui-btn" type='submit'>保存数据</button>
            <button class="layui-btn layui-btn-danger" type='button' data-confirm="确定要取消编辑吗？" data-close>取消编辑</button>
        </div>
    </div>
</form>