<?php
/**
 *  +-------------------------------------
 *  | MADE IN RETURN
 *  |-------------------------------------
 *  | Time: 2020/11/20
 *  | Author: CRAYOON <so.wo@foxmail.com>
 *  +--------------------------------------
 */

declare(strict_types=1);


namespace App\Model;


use Hyperf\DbConnection\Db;
use Hyperf\Redis\Redis;
use Hyperf\Utils\ApplicationContext;

class SysNode extends Model
{
    protected $table = "sys_node";

    /**
     * 获取需要授权节点
     * @return array
     */
    public static function getNodes(): array
    {
        $redis = ApplicationContext::getContainer()->get(Redis::class);
        if ($redis->exists("auth_nodes")) {
            $nodes = json_decode($redis->get("auth_nodes") ?? "", true);
        } else {
            $nodes = self::query()
                ->where("is_on", "=", 1)
                ->pluck("node")
                ->toArray();
            $redis->set("auth_nodes", json_encode($nodes));
        }
        return $nodes;
    }

    /**
     * 获取节点列表
     * @param bool $filter_on
     * @return array
     */
    public static function getList(bool $filter_on = true): array
    {
        return self::query()
            ->where(function ($query) use ($filter_on) {
                $filter_on && $query->where("is_on", "=", 1);
            })
            ->get()
            ->toArray();
    }

    public static function updateNode(array $node)
    {
        Db::beginTransaction();
        try {
            $old = self::getList(false);
            foreach ($old as &$item) {
                if (isset($node[$item['node']])) {
                    self::query()
                        ->where("id", "=", $item['id'])
                        ->update([
                            'is_on' => 1,
                            'title' => $node[$item['node']]['title']
                        ]);
                    unset($node[$item['node']]);
                } else {
                    self::query()->where("id", "=", $item['id'])->update(['is_on' => 0]);
                }
            }
            self::query()->insert($node);
            //清除缓存
            ApplicationContext::getContainer()->get(Redis::class)->del("auth_nodes");

            Db::commit();
        } catch (\Exception $exception) {
            Db::rollBack();
            return $exception->getMessage();
        }
        return true;
    }

    /*
     * 获取二级列表节点
     */
    public static function getLevelList($select = [])
    {
        $list = self::getList();
        $array = [];
        foreach ($list as $item) {
            $node = [
                "name" => $item['title'],
                "value" => $item['id']
            ];
            $temp = explode("/", $item['node']);
            if (!empty($temp)) {
                !isset($array[$temp[0]]) && $array[$temp[0]] = ["children" => []];
                in_array($item['id'], $select) && $node["selected"] = true;
                if (count($temp) < 2) {
                    $array[$temp[0]] += $node;
                } else {
                    $array[$temp[0]]["children"][] = $node;
                }
            }
        }
        return array_values($array);
    }
}