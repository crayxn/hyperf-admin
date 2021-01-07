@extends('common.content')
@section('style')
    <style>
        .list-sort-input{
            width: 32px!important;
            border: 1px solid #ddd;
        }
    </style>
@endsection
@section('button')
@endsection
@section('content')
    <div class="layui-col-lg12">
        <div class="layui-card overflow_auto">
            <div class="layui-card-header">
                @auth("$controller/add")
                    <a data-modal="/{{$controller}}/add" data-title="添加菜单"
                       class='layui-btn-success layui-btn layui-btn-sm '>添加</a>
                @endauth
                @auth("$controller/del")
                    <a data-table='ta' data-confirm="确认批量删除菜单?" data-rule='ids#{key}' data-field='delete' data-action='{{$controller}}/del'
                       class='layui-btn layui-btn-sm layui-btn-danger'>删除</a>
                @endauth

            </div>
            <div class="think-box-shadow table-block">
                @empty($list)
                    <blockquote class="layui-elem-quote">没 有 记 录 哦！</blockquote>
                @else
                    <table class="layui-table" lay-skin="line">
                        <thead>
                        <tr>
                            <th class='list-table-check-td think-checkbox'  width="20">
                                <label><input data-auto-none data-check-target='.list-check-box'
                                              type='checkbox'></label>
                            </th>
                            <th class='list-table-sort-td' width="40">
                                <button type="button" data-reload class="layui-btn layui-btn-xs">排序</button>
                            </th>
                            <th class='text-center' style="width:30px"></th>
                            <th style="width:230px"></th>
                            <th class='layui-hide-xs' style="width:180px"></th>
                            <th colspan="2"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $key=>$vo)
                            <tr data-dbclick>
                                <td class='list-table-check-td think-checkbox'>
                                    <label><input class="list-check-box" value='{{$vo['ids']}}' type='checkbox'></label>
                                </td>
                                <td class='list-table-sort-td'>
                                    <input data-action-blur="{{$controller}}/sort"
                                           data-value="id#{{$vo['id']}};action#sort;sort#{value}" data-loading="false"
                                           value="{{$vo['sort']}}" class="list-sort-input padding-left-5 padding-right-5 border-0">
                                </td>
                                <td class='text-center'><i class="{{$vo['icon']}} font-s18"></i></td>
                                <td class="nowrap"><span class="color-desc">{{$vo['spl']}}</span>{{$vo['title']}}</td>
                                <td class='layui-hide-xs'>{{$vo['url']}}</td>
                                <td class='text-center nowrap'>@if($vo['status'] == 0)<span
                                            class="color-red">已禁用</span>@else<span class="color-blue">使用中</span>@endif
                                </td>
                                <td class='text-center nowrap notselect'>
                                    @auth("$controller/add")
                                        @if($vo['spt'] < 2)
                                            <a class="layui-btn layui-btn-xs layui-btn-primary" data-title="添加子菜单"
                                               data-modal='{{$controller . '/add?pid=' .$vo['id']}}'>添 加</a>
                                        @else
                                            <a class="layui-btn layui-btn-xs layui-btn-disabled">添 加</a>
                                        @endif
                                    @endauth
                                    @auth("$controller/edit")
                                        <a data-dbclick class="layui-btn layui-btn-xs" data-title="编辑菜单"
                                           data-modal='{{$controller.'/edit?id='.$vo['id']}}'>编 辑</a>
                                    @endauth
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endempty
            </div>
        </div>
    </div>
@endsection
@section("script")
    <script>
        window.form.render();
    </script>
@endsection