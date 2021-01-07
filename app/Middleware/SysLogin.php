<?php
/**
 *  +-------------------------------------
 *  | MADE IN RETURN
 *  |-------------------------------------
 *  | Time: 2020/11/9
 *  | Author: CRAYOON <so.wo@foxmail.com>
 *  +--------------------------------------
 */

declare(strict_types=1);


namespace App\Middleware;


use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\Utils\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Qbhy\HyperfAuth\Authenticatable;
use Qbhy\HyperfAuth\AuthManager;
use Qbhy\HyperfAuth\Exception\UnauthorizedException;

class SysLogin implements MiddlewareInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var AuthManager
     */
    protected $auth;


    public function __construct(RequestInterface $request, HttpResponse $response, AuthManager $auth)
    {
        $this->request = $request;
        $this->response = $response;
        $this->auth = $auth;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        //获取令牌
        $token = $this->parseToken();
        try{
            $user = $this->auth->guard("jwt")->user($token);
            if(!$user instanceof Authenticatable){
                throw new UnauthorizedException("");
            }
        }catch (UnauthorizedException $exception){
            //判断
            $value = $this->request->server('HTTP_X_REQUESTED_WITH', "");
            if('xmlhttprequest' == strtolower($value)){
                return $this->response->json([
                    "code" => 1,
                    "msg" => "请先完成登陆",
                    "url" => "/sys_user/login"
                ]);
            }else{
                return $this->response->redirect("/sys_user/login");
            }

        }

        return $handler->handle($request);
    }

    private function parseToken()
    {
        $header = $this->request->header('Authorization', '');
        if (Str::startsWith($header, 'Bearer ')) {
            return Str::substr($header, 7);
        }

        if ($this->request->has('token')) {
            return $this->request->input('token');
        }
        //增加 支持cookie
        if ($this->request->hasCookie("Authorization")) {
            return $this->request->cookie("Authorization");
        }

        return null;
    }
}