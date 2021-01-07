<?php

declare(strict_types=1);

namespace App\Middleware;

use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\Redis\Redis;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Csrf implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Redis
     */
    protected $redis;

    /**
     * @var HttpResponse
     */
    protected $response;


    public function __construct(ContainerInterface $container, HttpResponse $response)
    {
        $this->container = $container;
        $this->response = $response;
        $this->redis = $container->get(Redis::class);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $node = rtrim(preg_replace('/\?.*/', '', (string)$request->getUri()));
        if ($request->getMethod() == "POST") {
            $param = $request->getParsedBody();
            $token = $param['_token'] ?? "";
            $res = $this->check($token, $node);
            if (!$res) {
                return $this->response
                    ->json([
                        "code" => 1,
                        "msg" => "令牌失效,刷新重试",
                        "node" => $node,
                        "cache" => $this->redis->get("csrf:$token"),
                        "wait" => 3
                    ]);
            }
        }
        $prams = $request->getParsedBody();
        $prams["_token"] = $this->create($node);
        $request = $request->withParsedBody($prams);
        return $handler->handle($request);
    }

    /**
     * 创建
     * @param $node
     * @return string
     */
    private function create($node)
    {
        $token = sha1(time() . rand(1000, 9999));
        $this->redis->set("csrf:$token", $node, 3600); //1小时有效
        return $token;
    }

    /**
     * @param $token
     * @param $node
     * @return bool
     */
    private function check($token, $node): bool
    {
        if ($token && $this->redis->exists("csrf:$token")) {
            $cache_node = $this->redis->get("csrf:$token");
            if ($cache_node == $node) {
            } elseif (strpos($node, str_replace("/index", "", $cache_node)) !== false) {
            } else {
                return false;
            }
            $this->redis->del("csrf:$token");
            return true;
        } else {
            return false;
        }
    }
}