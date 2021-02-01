<?php
/**
 *  +-------------------------------------
 *  | MADE IN RETURN
 *  |-------------------------------------
 *  | Time: 2021/2/1
 *  | Author: CRAYOON <so.wo@foxmail.com>
 *  +--------------------------------------
 */

declare(strict_types=1);


namespace App\Rpc;

use Hyperf\RpcServer\Annotation\RpcService;

/**
 * @RpcService(name="DemoService", protocol="jsonrpc", server="jsonrpc")
 * Class DemoService
 * @package App\Rpc
 */
class DemoService implements CalculatorServiceInterface
{
    public function add(int $a, int $b): int
    {
        // 这里是服务方法的具体实现
        return $a + $b;
    }
}