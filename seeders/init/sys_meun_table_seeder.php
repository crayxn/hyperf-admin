<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;

class SysMeunTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Hyperf\DbConnection\Db::table("sys_menu")->insert([
            [
                "pid" => 0,
                "title" => "系统设置",
                "icon" => "",
                "url" => "#",
                "params" => "",
                "sort" => 99,
                "status" => 1,
                "created_at" => time(),
                "updated_at" => time()
            ],
            [
                "pid" => 1,
                "title" => "账号管理",
                "icon" => "iconfont icon-account fa",
                "url" => "sys_user/index",
                "params" => "",
                "sort" => 2,
                "status" => 1,
                "created_at" => time(),
                "updated_at" => time()
            ], [
                "pid" => 1,
                "title" => "访问权限",
                "icon" => "iconfont icon-set fa",
                "url" => "sys_auth/index",
                "params" => "",
                "sort" => 3,
                "status" => 1,
                "created_at" => time(),
                "updated_at" => time()
            ], [
                "pid" => 1,
                "title" => "系统日志",
                "icon" => "iconfont icon-calendar fa",
                "url" => "sys_log/index",
                "params" => "",
                "sort" => 1,
                "status" => 1,
                "created_at" => time(),
                "updated_at" => time()
            ], [
                "pid" => 1,
                "title" => "系统菜单",
                "icon" => "layui-icon layui-icon-spread-left",
                "url" => "sys_menu/index",
                "params" => "",
                "sort" => 4,
                "status" => 1,
                "created_at" => time(),
                "updated_at" => time()
            ]
        ]);
    }
}
