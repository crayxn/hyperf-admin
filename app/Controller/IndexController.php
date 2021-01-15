<?php

declare(strict_types=1);

namespace App\Controller;


use App\Extend\Tools;
use App\Model\SysMenu;

class IndexController extends BaseController
{
    /**
     * @return array|\Psr\Http\Message\ResponseInterface
     */
    public function index()
    {
        $user = $this->userInfo();
        $menu = $user->id == 1 ? SysMenu::getList() :SysMenu::getListByAuth(explode(",", $user->authorize ?? ""));
        return $this->view([
            "user" => $user,
            "v" => date("md"),
            "app_name" => "🚀_admin_",
            "menus" => Tools::arr2tree($menu)
        ]);
    }
}
