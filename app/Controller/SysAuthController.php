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


namespace App\Controller;

use App\Annotation\Node;
use App\Middleware\Csrf;
use App\Model\SysAuth;
use App\Model\SysNode;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;

/**
 * Class SysAuthController
 * @AutoController()
 * @Middlewares({
 *     @Middleware(Csrf::class)
 * })
 * @package App\Controller
 */
class SysAuthController extends BaseController
{
    /**
     * @Node("权限管理")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index()
    {
        return $this->paginate(SysAuth::query(), ["title" => "权限管理"]);
    }

    /**
     * @Node("添加权限")
     * @return array|\Psr\Http\Message\ResponseInterface
     */
    public function add()
    {
        if ($this->request->isMethod("POST")) {
            $param = $this->request->post();
            Db::beginTransaction();
            //插入
            $auth_id = SysAuth::query()->insertGetId([
                "name" => $param['name'],
                "desc" => $param['desc'] ?? "",
                "created_at" => time(),
                "updated_at" => time(),
            ]);
            $res = true;
            //有选择节点 情况
            if (!empty($param['node_ids'])) {
                $res = SysAuth::updateNode($auth_id, explode(",", $param['node_ids']));
            }
            if (!$auth_id || !$res) {
                Db::rollBack();
                return $this->error("添加失败,稍后重试");
            }
            Db::commit();
            return $this->success("添加成功");
        }
        return $this->view([
            "title" => "添加",
            "node" => SysNode::getLevelList()
        ], 'form');
    }

    /**
     * @Node("编辑权限")
     * @return array|\Psr\Http\Message\ResponseInterface
     */
    public function edit()
    {
        $id = $this->request->input("id");
        if (!$id) return $this->error("id不能为空");

        if ($this->request->isMethod("POST")) {
            $param = $this->request->post();
            Db::beginTransaction();
            //更新
            SysAuth::query()
                ->where("id", "=", $id)
                ->update([
                    "name" => $param['name'],
                    "desc" => $param['desc'] ?? ""
                ]);
            $res = true;
            //有选择节点 情况
            if (!empty($param['node_ids'])) {
                $res = SysAuth::updateNode($id, explode(",", $param['node_ids']));
            }
            if (!$res) {
                Db::rollBack();
                return $this->error("保存失败,稍后重试");
            }
            Db::commit();
            return $this->success("保存成功");
        }
        $vo = SysAuth::query()->find($id);
        if (!$vo) return $this->error("请求失败,稍后重试");
        $select = Db::table("sys_auth_node")
            ->where("auth_id", $id)
            ->pluck("node_id")
            ->toArray();
        return $this->view([
            "title" => "编辑",
            "node" => SysNode::getLevelList($select),
            "vo" => $vo
        ], 'form');
    }

    /**
     * @Node("删除权限")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function del()
    {
        $id = $this->request->post("id", "");
        SysAuth::query()->whereIn("id", explode(",", $id))->update(['is_deleted' => 1]);
        return $this->success();
    }
}