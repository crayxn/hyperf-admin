<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use App\Extend\Paginate;
use App\Extend\Tools;
use duncan3dc\Laravel\BladeInstance;
use Hyperf\Database\Model\Model;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\View\RenderInterface;
use Psr\Container\ContainerInterface;
use Qbhy\HyperfAuth\Authenticatable;
use Qbhy\HyperfAuth\AuthManager;

abstract class BaseController
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject()
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @Inject()
     * @var RenderInterface
     */
    protected $render;

    /**
     * @Inject()
     * @var Paginate
     */
    protected $paginate;

    /**
     * @Inject()
     * @var AuthManager
     */
    protected $auth;

    /**
     * 获取用户信息
     * @return Authenticatable|null
     */
    protected function userInfo(): ?Authenticatable
    {
        $token = $this->request->cookie("Authorization", null);
        return $token ? $this->auth->guard("jwt")->user($token) : null;
    }

    /**
     * 返回视图
     * @param array $data
     * @param string $template
     * @return array|\Psr\Http\Message\ResponseInterface
     */
    protected function view(array $data = [], $template = "")
    {
        $dispatched = $this->request->getAttribute(Dispatched::class);
        $callback = $dispatched->handler->callback;
        if (is_string($callback)) {
            if (strpos($callback, '::')) {
                $callback = explode("::", $callback);
            } elseif (strpos($callback, "@")) {
                $callback = explode("@", $callback);
            } else {
                $callback = ["", "no_found"];
            }
        }
        list($controller, $func) = $callback;
        $template = empty($template) ? $func : $template;
        $controller_name = basename(str_replace('\\', '/', $controller));
        $controller = str_replace('_controller', '', Tools::parseName($controller_name, 0));
        return $this->render->render("{$controller}.{$template}", array_merge($data, [
            "controller" => $controller,
            "url" => $this->request->url(),
            "sys_user" => $this->userInfo(),
            "param" => $this->request->getParsedBody()
        ]));
    }

    protected function isAjax()
    {
        $value = $this->request->server('HTTP_X_REQUESTED_WITH', "");
        return 'xmlhttprequest' == strtolower($value);
    }

    protected function success($msg = "成功", $data = [], $code = 0, $url = "", $wait = 3)
    {
        return $this->response
            ->json([
                "code" => $code,
                "msg" => $msg,
                "data" => $data,
                "url" => $url,
                'wait' => $wait,
                'new_token' => $this->request->post("_token") ?? false
            ]);
    }

    protected function error($msg = "错误", $data = [], $url = "", $wait = 3)
    {
        return $this->response
            ->json([
                "code" => 1,
                "msg" => $msg,
                "data" => $data,
                "url" => $url,
                'wait' => $wait,
                'new_token' => $this->request->post("_token") ?? false
            ]);
    }

    /**
     * 分页
     * @param mixed $db
     * @param array $data
     * @param int $limit
     * @param string $template
     * @param string $order
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function paginate($db, array $data = [], $limit = 0, string $template = '', string $order = 'id')
    {
        if (!$this->request->input("page", false)) {
            return $this->view($data, $template);
        }
        if ($limit > 0) {
            $limit = intval($limit);
        } else {
            $limit = $this->request->input('limit', 10);
        }

        $pager = $db->where('is_deleted',0)
            ->orderBy($order,'desc')
            ->paginate(intval($limit));
        return $this->success("", [
            'list' => $pager->items(),
            'count' => $pager->total()
        ]);
    }

}
