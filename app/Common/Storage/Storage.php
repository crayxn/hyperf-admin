<?php
/**
 *  +-------------------------------------
 *  | MADE IN RETURN
 *  |-------------------------------------
 *  | Time: 2020/12/9
 *  | Author: CRAYOON <so.wo@foxmail.com>
 *  +--------------------------------------
 */

declare(strict_types=1);


namespace App\Common\Storage;


use Hyperf\Di\Exception\Exception;

class Storage
{
    /**
     * 获取
     * @param $class
     * @return mixed
     * @throws Exception
     */
    public static function init($class): StorageSource
    {
        $class = ucfirst(strtolower($class));
        if (class_exists($object = "App\\Common\\Storage\\{$class}")) {
            return make($object);
        } else {
            throw new Exception("File driver [{$class}] does not exist.");
        }
    }
}