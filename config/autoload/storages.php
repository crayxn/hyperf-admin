<?php
/**
 *  +-------------------------------------
 *  | MADE IN RETURN
 *  |-------------------------------------
 *  | Time: 2020/12/9
 *  | Author: CRAYOON <so.wo@foxmail.com>
 *  +--------------------------------------
 */

return [
    'oss' => [
        'key' => env("OSS_KEY", ""),
        'secret' => env("OSS_SECRET", ""),
        'domain' => env("OSS_DOMAIN", ""),
        'bucket' => env("OSS_BUCKET", ""),
        'endpoint' => env("OSS_ENDPOINT", ""),
        'cdn' => env("OSS_CDN", ""),
        'folder' => env("OSS_FOLDER", "test"), //指定存放位置
    ],
];