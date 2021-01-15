<?php
/**
 *  +-------------------------------------
 *  | MADE IN RETURN
 *  |-------------------------------------
 *  | Time: 2020/10/27
 *  | Author: CRAYOON <so.wo@foxmail.com>
 *  +--------------------------------------
 */


namespace App\Controller;

use App\Annotation\Node;
use App\Middleware\Csrf;
use App\Middleware\SysLogin;
use App\Model\SysAuth;
use App\Model\SysLog;
use App\Model\SysUser;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Cookie\Cookie;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Qbhy\HyperfAuth\Authenticatable;

/**
 * @AutoController()
 * Class SysUserController
 * @package App\Controller
 */
class SysUserController extends BaseController
{
    /**
     * @Inject()
     * @var ValidatorFactoryInterface
     */
    protected $validationFactory;

    /**
     * 登陆
     * @return mixed
     * @Middlewares({
     *     @Middleware(Csrf::class)
     * })
     */
    public function login()
    {
        if ($this->request->isMethod("POST")) {
            $param = $this->request->post();
            $validator = $this->validationFactory->make(
                $param,
                [
                    'name' => 'required',
                    'password' => 'required|min:6',
                ],
                [
                    'name.required' => '账户不能为空',
                    'password.required' => '请输入正确密码',
                    'password.min' => '请输入正确密码',
                ]
            );

            if ($validator->fails()) {
                return $this->error($validator->errors()->first());
            }
            $user = SysUser::query()->where("name", "=", $param['name'])->first();
            if (!$user) return $this->error("登陆失败,稍后重试");
            if ($user->password != md5($user->salt . $param['password'])) return $this->error("账户或密码错误,请重新输入");
            $token = $this->auth->guard("jwt")->login($user);
            $cookie = new Cookie("Authorization", "$token");
            //记录日志
            SysLog::add("", "登陆后台", $user->id);
            return $this->response->withCookie($cookie)->json([
                "code" => 0,
                "msg" => "登陆成功",
                "url" => "/"
            ]);
        }
        return $this->view([
            "v" => date("md"),
            "app_name" => "🚀_admin_",
        ]);
    }

    /**
     * 修改个人信息
     * @Middlewares({
     *     @Middleware(SysLogin::class),
     *     @Middleware(Csrf::class)
     * })
     */
    public function user()
    {
        $user = $this->userInfo();
        if ($this->request->isMethod("POST")) {
            $param = $this->request->post();
            $validator = $this->validationFactory->make(
                $param,
                [
                    'phone' => 'required',
                    'name' => 'required',
                ],
                [
                    'phone.required' => '手机号码不能为空',
                    'name.required' => '请输入正确密码',
                ]
            );
            if ($validator->fails()) {
                return $this->error($validator->errors()->first());
            }
            SysUser::query()
                ->where("id", "=", $user->id)
                ->update([
                    "name" => $param['name'],
                    "phone" => $param['phone'],
                    "remark" => $param['remark'],
                    "avatar" => $param['avatar'] ?? "",
                ]);
            return $this->success("修改成功");
        } else {
            if (!$user instanceof Authenticatable) {
                return $this->error("请求失败,稍后重试");
            }
            return $this->view(compact("user"), 'form');
        }
    }

    /**
     * @Middlewares({
     *     @Middleware(SysLogin::class),
     *     @Middleware(Csrf::class)
     * })
     */
    public function logout()
    {
        $token = $this->request->cookie("Authorization", "");
        $this->auth->guard("jwt")->logout($token);
        $cookie = new Cookie("Authorization", "");
        return $this->response->withCookie($cookie)->json([
            "code" => 0,
            "msg" => "成功退出登陆",
            "url" => "/sys_user/login",
        ]);
    }

    /**
     * @Node("用户管理")
     * @Middlewares({
     *     @Middleware(Csrf::class)
     * })
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index()
    {
        $query = SysUser::query()
            ->where("id", "<>", 0);
        return $this->paginate($query, ["title" => "用户管理"]);
    }

    /**
     * @Node("添加用户")
     * @Middlewares({
     *     @Middleware(Csrf::class)
     * })
     * @return array|\Psr\Http\Message\ResponseInterface
     */
    public function add()
    {
        if ($this->request->isMethod("POST")) {
            $param = $this->request->post();
            $validator = $this->validationFactory->make(
                $param,
                [
                    'phone' => 'required',
                    'name' => 'required',
                ],
                [
                    'phone.required' => '手机号码不能为空',
                    'name.required' => '账户名称不能为空',
                ]
            );

            if ($validator->fails()) {
                return $this->error($validator->errors()->first());
            }
            $salt = md5($param['phone'] . time());
            $is_exist = SysUser::query()->where("name","=",$param['name'])->first();
            if($is_exist) return $this->error("添加失败,账户已被使用");
            $res = SysUser::query()->insert([
                "phone" => $param['phone'],
                "name" => $param['name'],
                "avatar" => $param['avatar'] ?? "",
                "remark" => $param['remark'] ?? "",
                "authorize" => implode(",", $param['authorize'] ?? []),
                "salt" => $salt,
                "password" => md5($salt . $param['phone']),
            ]);
            return $res ? $this->success("添加成功") : $this->error("添加失败,稍后重试");
        }
        return $this->view([
            "authorizes" => SysAuth::query()->pluck("name", "id")
        ], 'form');
    }

    /**
     * @Node("编辑用户")
     * @Middlewares({
     *     @Middleware(Csrf::class)
     * })
     * @return array|\Psr\Http\Message\ResponseInterface
     */
    public function edit()
    {
        $id = $this->request->input("id");
        if (!$id) return $this->error("id 不能为空");

        if ($this->request->isMethod("POST")) {
            $param = $this->request->post();
            $validator = $this->validationFactory->make(
                $param,
                [
                    'phone' => 'required',
                    'name' => 'required',
                ],
                [
                    'phone.required' => '手机号码不能为空',
                    'name.required' => '请输入正确姓名',
                ]
            );
            if ($validator->fails()) {
                return $this->error($validator->errors()->first());
            }
            SysUser::query()
                ->where("id", "=", $id)
                ->update([
                    "name" => $param['name'],
                    "phone" => $param['phone'],
                    "remark" => $param['remark'],
                    "avatar" => $param['avatar'] ?? "",
                    "authorize" => implode(",", $param['authorize'] ?? []),
                ]);
            return $this->success("修改成功");
        } else {
            $user = SysUser::query()->find($id);
            if (!$user) return $this->error("请求失败，稍后重试");
            $user['authorize'] = explode(",", $user['authorize']);
            $authorizes = SysAuth::query()->pluck("name", "id");
            return $this->view(compact("user", "authorizes"), 'form');
        }
    }

    /**
     * @Node("禁用用户")
     * @Middlewares({
     *     @Middleware(SysLogin::class),
     *     @Middleware(Csrf::class)
     * })
     */
    public function forbid()
    {
        $id = $this->request->post("id", "");
        SysUser::query()->whereIn("id", explode(",", $id))->update(['status' => 0]);
        return $this->success();
    }

    /**
     * @Node("启用用户")
     * @Middlewares({
     *     @Middleware(SysLogin::class),
     *     @Middleware(Csrf::class)
     * })
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function resume()
    {
        $id = $this->request->post("id", "");
        SysUser::query()->whereIn("id", explode(",", $id))->update(['status' => 1]);
        return $this->success();
    }

    /**
     * @Node("删除用户")
     * @Middlewares({
     *     @Middleware(SysLogin::class),
     *     @Middleware(Csrf::class)
     * })
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function del()
    {
        $id = $this->request->post("id", "");
        SysUser::query()->whereIn("id", explode(",", $id))->update(['is_deleted' => 1]);
        return $this->success();
    }

    /**
     * @Node("修改密码")
     * @Middlewares({
     *     @Middleware(SysLogin::class),
     *     @Middleware(Csrf::class)
     * })
     */
    public function pwd()
    {
        if ($this->request->isMethod("POST")) {
            $param = $this->request->post();
            $validator = $this->validationFactory->make(
                $param,
                [
                    'id' => 'required',
                    'password' => 'required|min:6',
                    'repassword' => 'required',
                ],
                [
                    'id.required' => 'ID不能为空',
                    'password.required' => '密码不能为空',
                    'password.min' => '密码必须多于或等于6个字符',
                    'repassword.min' => '请先再次输入密码',
                ]
            );
            if ($validator->fails()) return $this->error($validator->errors()->first());
            if ($param['password'] !== $param['repassword']) return $this->error("两次密码输入不一致");
            $user = SysUser::query()
                ->where("id", "=", $param['id'])
                ->first();
            if (!$user) return $this->error("保存失败,稍后重试");
            $user->password = md5($user->salt . $param['password']);
            $user->save();
            return $this->success("保存成功");
        } else {
            $id = $this->request->input("id", "");
            if (empty($id)) {
                return $this->error("请求失败,稍后重试");
            }
            return $this->view(["id" => $id]);
        }
    }

    public function pwdSelf()
    {
        if ($this->request->isMethod("POST")) {
            $param = $this->request->post();
            $validator = $this->validationFactory->make(
                $param,
                [
                    'password' => 'required|min:6',
                    'repassword' => 'required',
                ],
                [
                    'password.required' => '密码不能为空',
                    'password.min' => '密码必须多于或等于6个字符',
                    'repassword.min' => '请先再次输入密码',
                ]
            );
            if ($validator->fails()) return $this->error($validator->errors()->first());
            if ($param['password'] !== $param['repassword']) return $this->error("两次密码输入不一致");
            $user = $this->userInfo();
            $password = md5($user['salt'] . $param['password']);
            SysUser::query()
                ->where("id", "=", $user['id'])
                ->update(["password" => $password]);
            return $this->success("保存成功");
        } else {
            return $this->view([], 'pwd');
        }
    }
}