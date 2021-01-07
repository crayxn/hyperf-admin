<?php
/**
 *  +-------------------------------------
 *  | MADE IN RETURN
 *  |-------------------------------------
 *  | Time: 2020/10/29
 *  | Author: CRAYOON <so.wo@foxmail.com>
 *  +--------------------------------------
 */
declare(strict_types=1);

namespace App\Engine;


use App\Model\SysAuth;
use duncan3dc\Laravel\BladeInstance;
use Hyperf\View\Engine\EngineInterface;

class BladeEngine implements EngineInterface
{
    public function render($template, $data, $config): string
    {
        $blade = new BladeInstance($config['view_path'], $config['cache_path']);
        $blade = $blade
            //日期
            ->directive('datetime', function ($expression, $format = 'Y-m-d H:i:s') {
                return "<?php echo date('$format',intval($expression)); ?>";
            })
            //权限验证
            ->if("auth", function ($node) use ($data) {
                if (!isset($data['sys_user'])) {
                    return false;
                } elseif ($data['sys_user']->id == 1) {
                    return true;
                }
                return SysAuth::checkNode(explode(",", $data['sys_user']->authorize ?? ""), $node);
            })
            ->directive("_token", function () {
                return "<?php echo isset(\$param['_token']) ? '<input type=\"hidden\" name=\"_token\" value=\"'.\$param['_token'].'\"/>':'' ?>";
            });
        unset($data['sys_user']);
        return $blade->render($template, $data);
    }
}