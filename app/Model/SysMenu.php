<?php
/**
 *  +-------------------------------------
 *  | MADE IN RETURN
 *  |-------------------------------------
 *  | Time: 2020/10/27
 *  | Author: CRAYOON <so.wo@foxmail.com>
 *  +--------------------------------------
 */


namespace App\Model;


class SysMenu extends Model
{
    protected $table = "sys_menu";

    public static function getListByAuth(array $auths)
    {
        //获取节点
        $nodes = SysNode::getNodes();
        $menus = self::getList();
        $pass_nodes = SysAuth::getNodeByAuth($auths);
        foreach ($menus as $key => $item) {
            if (in_array($item['url'], $nodes) && !in_array($item['url'], $pass_nodes)) {
                unset($menus[$key]);
            }
        }
        return $menus;
    }

    public static function getList(){
        return SysMenu::query()->where("status","=",1)->orderByDesc("sort")->get()->toArray();
    }
}