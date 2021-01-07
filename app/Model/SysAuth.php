<?php
/**
 *  +-------------------------------------
 *  | MADE IN RETURN
 *  |-------------------------------------
 *  | Time: 2020/11/23
 *  | Author: CRAYOON <so.wo@foxmail.com>
 *  +--------------------------------------
 */

declare(strict_types=1);


namespace App\Model;


use Hyperf\DbConnection\Db;
use Hyperf\Redis\Redis;
use Hyperf\Utils\ApplicationContext;

class SysAuth extends Model
{
    protected $table = "sys_auth";


    /**
     * 更新权限节点
     * @param int $auth
     * @param array $nodes
     * @return bool
     */
    public static function updateNode($auth, array $nodes)
    {
        if (!$auth) return false;
        //删除旧数据
        Db::table("sys_auth_node")
            ->where("auth_id", "=", $auth)
            ->delete();
        //获取节点列表
        $list = SysNode::query()
            ->where("is_on", "=", 1)
            ->whereIn("id", $nodes)
            ->select('id as node_id', 'node')
            ->get()
            ->toArray();
        foreach ($list as &$item) $item['auth_id'] = $auth;
        //插入新的 节点
        $redis = ApplicationContext::getContainer()->get(Redis::class);
        $redis->set("auth_node:$auth", json_encode(array_column($list, "node")));
        return Db::table("sys_auth_node")->insert($list);
    }

    /**
     * 检查节点
     * @param array $auths
     * @param string $node
     * @return bool
     */
    public static function checkNode(array $auths, string $node): bool
    {
        if (count($auths)) {
            foreach ($auths as $auth) {
                $temp = self::getNode(intval($auth));
                if (in_array(trim($node, "/"), $temp)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 根据权限 获取能用节点
     * @param array $auths
     * @return array
     */
    public static function getNodeByAuth(array $auths): array
    {
        $nodes = [];
        foreach ($auths as $auth) {
            $nodes = array_unique($nodes + self::getNode(intval($auth)));
        }
        return $nodes;
    }

    /**
     * 根据权限获取节点
     * @param int $auth
     * @return array|null
     */
    protected static function getNode(int $auth): ?array
    {
        $redis = ApplicationContext::getContainer()->get(Redis::class);
        $key = "auth_node:$auth";
        if ($redis->exists($key)) {
            $nodes = json_decode($redis->get($key) ?? "", true);
        } else {
            $nodes = Db::table("sys_auth_node")
                ->where("auth_id", $auth)
                ->pluck("node")
                ->toArray();
            $redis->set($key, json_encode($nodes));
        }
        return $nodes;
    }
}