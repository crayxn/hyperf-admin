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


namespace App\Aspect;


use App\Annotation\Node;
use App\Model\SysAuth;
use App\Model\SysLog;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpServer\Router\RouteCollector;
use Hyperf\Utils\Str;
use Illuminate\Support\Facades\DB;
use Qbhy\HyperfAuth\Authenticatable;
use Qbhy\HyperfAuth\AuthManager;
use Qbhy\HyperfAuth\Exception\UnauthorizedException;

/**
 * Class NodeCheckAspect
 * @Aspect()
 * @package App\Aspect
 */
class NodeCheckAspect extends AbstractAspect
{
    // 要切入的类，可以多个，亦可通过 :: 标识到具体的某个方法，通过 * 可以模糊匹配
    public $classes = [];

    // 要切入的注解，具体切入的还是使用了这些注解的类，仅可切入类注解和类方法注解
    public $annotations = [
        Node::class,
    ];

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


    public function __construct(RequestInterface $request, ResponseInterface $response, AuthManager $auth)
    {
        $this->request = $request;
        $this->response = $response;
        $this->auth = $auth;
    }

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        //获取用户
        $token = $this->parseToken();
        $route = $this->request->getUri()->getPath();
        try {
            $user = $this->auth->guard("jwt")->user($token);
            if (!$user instanceof Authenticatable) {
                throw new UnauthorizedException("");
            }
            if ($user->id != 1) {
                //判断权限
                if (!SysAuth::checkNode(explode(",", $user->authorize ?? ""), $route)) {
                    return $this->response->json([
                        "code" => 401,
                        "msg" => "无权限访问"
                    ]);
                };
            }
        } catch (UnauthorizedException $exception) {
            //判断
            $value = $this->request->server('HTTP_X_REQUESTED_WITH', "");
            if ('xmlhttprequest' == strtolower($value)) {
                return $this->response->json([
                    "code" => 1,
                    "msg" => "请先完成登陆",
                    "url" => "/sys_user/login"
                ]);
            } else {
                return $this->response->redirect("/sys_user/login");
            }
        }
        if ($this->request->isMethod("POST")) {
            //插入记录
            $action = $proceedingJoinPoint->getAnnotationMetadata()->method[Node::class]->value ?? "";
            SysLog::query()->insert(["node" => $route, "action" => $action, "sys_user_id" => $user->id, "created_at" => time()]);
        }

        return $proceedingJoinPoint->process();
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