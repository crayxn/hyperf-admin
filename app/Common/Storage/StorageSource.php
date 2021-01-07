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


interface StorageSource
{
    /**
     * 获取上传参数
     * @param string $file_md5
     * @param int $expire
     * @param string $name
     * @return array
     */
    public function getUploadParam(string $file_md5,int $expire,?string $name): array ;

    /**
     * 查找文件
     * @param string $name
     * @param string|null $attname
     * @return array
     */
    public function findFile(string $name, ?string $attname = null): array ;
}