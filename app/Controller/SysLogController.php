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


namespace App\Controller;


use App\Annotation\Node;
use App\Model\SysLog;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * Class SysAuthController
 * @AutoController()
 * @package App\Controller
 */
class SysLogController extends BaseController
{
    /**
     * @Node("系统日志")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index()
    {
        $query = SysLog::query()->with("user");
        return $this->paginate($query, ["title" => "系统日志"]);
    }

    /**
     * @Node("删除日志")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function del()
    {
        $ids = $this->request->post("ids", "");
        if (!empty($ids)) {
            SysLog::query()
                ->whereIn("id", explode(",", $ids))
                ->update(['is_deleted' => 1]);
        }
        return $this->success("删除成功");
    }

}