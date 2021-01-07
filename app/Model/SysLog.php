<?php
/**
 *  +-------------------------------------
 *  | MADE IN RETURN
 *  |-------------------------------------
 *  | Time: 2020/12/29
 *  | Author: CRAYOON <so.wo@foxmail.com>
 *  +--------------------------------------
 */

declare(strict_types=1);


namespace App\Model;


class SysLog extends Model
{
    protected $table = "sys_log";

    public function user()
    {
        return self::belongsTo(SysUser::class, "sys_user_id", "id");
    }

    public static function add(string $node, string $action, int $user_id)
    {
        return SysLog::query()->insert([
            "node" => $node,
            "action" => $action,
            "sys_user_id" => $user_id,
            "created_at" => time()
        ]);
    }
}