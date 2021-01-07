<?php
/**
 *  +-------------------------------------
 *  | MADE IN RETURN
 *  |-------------------------------------
 *  | Time: 2020/12/8
 *  | Author: CRAYOON <so.wo@foxmail.com>
 *  +--------------------------------------
 */

declare(strict_types=1);


namespace App\Controller;


use App\Annotation\Node;
use App\Common\DataExtend;
use App\Model\SysMenu;
use App\Model\SysNode;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * Class SysMenuController
 * @package App\Controller
 * @AutoController()
 */
class SysMenuController extends BaseController
{
    /**
     * @Node("系统菜单管理")
     */
    public function index()
    {
        $data = SysMenu::query()->orderByDesc("sort")->get()->toArray();
        foreach ($data as &$vo) {
            $vo['ids'] = join(',', DataExtend::getArrSubIds($data, $vo['id']));
        }
        $data = DataExtend::arr2table($data);
        return $this->view(['title' => "系统菜单管理", "list" => $data]);
    }

    public function sort()
    {
        $param = $this->request->post();
        if (isset($param['id']) && !empty($param['id'])) {
            SysMenu::query()
                ->where("id", "=", $param['id'])
                ->update(['sort' => $param['sort'] ?? 1]);
        }
        return $this->success("更新成功");
    }

    /**
     * @Node("添加系统菜单")
     * @return array|\Psr\Http\Message\ResponseInterface
     */
    public function add()
    {
        if ($this->request->isMethod("POST")) {
            $param = $this->request->post();
            $res = SysMenu::query()->insert($param);
            if ($res) {
                return $this->success("添加成功");
            } else {
                return $this->error("添加失败,稍后重试");
            }
        }
        $vo = [];
        return $this->view(array_merge($this->_form($vo), [
            "vo" => $vo,
            "title" => "添加系统菜单",
        ]), 'form');
    }


    /**
     * @Node("编辑系统菜单")
     * @return array|\Psr\Http\Message\ResponseInterface
     */
    public function edit()
    {
        if ($this->request->isMethod("POST")) {
            $param = $this->request->post();
            $res = SysMenu::query()->where("id", "=", $param['id'])->update($param);
            if ($res) {
                return $this->success("更新成功");
            } else {
                return $this->error("更新失败,稍后重试");
            }
        }
        $vo = SysMenu::query()->find($this->request->query("id", 0));
        if (!$vo) return $this->error("请求失败,稍后重试");
        return $this->view(array_merge($this->_form($vo), [
            "vo" => $vo,
            "title" => "添加系统菜单",
        ]), 'form');
    }

    protected function _form(&$vo): array
    {
        /* 选择自己的上级菜单 */
        $vo['pid'] = $vo['pid'] ?? $this->request->input('pid', '0');
        /* 读取系统功能节点 */
        $nodes = SysNode::getList();
        /* 列出可选上级菜单 */
        $menus = SysMenu::query()
            ->orderByDesc('sort')
            ->get()
            ->keyBy('id')
            ->toArray();
        $menus = DataExtend::arr2table(array_merge($menus, [['id' => '0', 'pid' => '-1', 'url' => '#', 'title' => '顶部菜单']]));
        if (isset($vo['id'])) foreach ($menus as $menu) if ($menu['id'] === $vo['id']) $vo = $menu;
        foreach ($menus as $key => $menu) if ($menu['spt'] >= 3 || $menu['url'] !== '#') unset($menus[$key]);
        if (isset($vo['spt']) && isset($vo['spc']) && in_array($vo['spt'], [1, 2]) && $vo['spc'] > 0) {
            foreach ($menus as $key => $menu) if ($vo['spt'] <= $menu['spt']) unset($menus[$key]);
        }
        return compact("nodes", "menus");
    }

    /**
     * @Node("删除菜单")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function del()
    {
        $ids = $this->request->post("ids", "");
        if (!empty($ids)) {
            SysMenu::query()
                ->whereIn("id", explode(",", $ids))
                ->delete();
        }
        return $this->success("删除成功");
    }
}