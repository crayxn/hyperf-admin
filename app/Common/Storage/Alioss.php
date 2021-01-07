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


use Hyperf\Contract\ConfigInterface;
use OSS\OssClient;

class Alioss implements StorageSource
{

    /**
     * oss 连接
     * @var OssClient
     */
    protected $client;

    /**
     * 配置
     * @var mixed
     */
    protected $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config->get("storages.oss");
        $this->client = make(OssClient::class, [
            "accessKeyId" => $this->config['key'] ?? "",
            "accessKeySecret" => $this->config['secret'] ?? "",
            "endpoint" => $this->config['endpoint'] ?? "",
            "isCName" => false
        ]);
    }

    /**
     * 获取上传参数
     * @param string $file_md5
     * @param int $expire
     * @param string|null $name
     * @return array
     */
    public function getUploadParam(string $file_md5, int $expire, ?string $name): array
    {
        $token = $this->buildUploadToken($file_md5, $expire, $name);
        $data['url'] = $token['siteurl'];
        $data['policy'] = $token['policy'];
        $data['signature'] = $token['signature'];
        $data['OSSAccessKeyId'] = $token['keyid'];
        $data['server'] = $this->upload();
        return $data;
    }

    /**
     * 查找文件是否存在
     * @param string $name
     * @param string|null $attname
     * @return array
     */
    public function findFile(string $name,?string $attname = null): array
    {
        return $this->client->doesObjectExist($this->config['bucket'] ?? "", $name) ? [
            'url' => $this->url($name, $attname),
            'key' => $name
        ] : [];
    }

    /**
     * 构建上传令牌
     * @param string $name 文件名称
     * @param integer $expires 有效时间
     * @param null|string $attname 下载名称
     * @return array
     */
    private function buildUploadToken(string $name, int $expires = 3600, ?string $attname = null): array
    {
        $data = [
            'policy' => base64_encode(json_encode([
                'conditions' => [['content-length-range', 0, 1048576000]],
                'expiration' => date('Y-m-d\TH:i:s.000\Z', time() + $expires),
            ])),
            'keyid' => $this->config["key"] ?? "",
            'siteurl' => $this->url($name, $attname),
        ];
        $data['signature'] = base64_encode(hash_hmac('sha1', $data['policy'], $this->config["secret"] ?? "", true));
        return $data;
    }

    /**
     * 获取文件当前URL地址
     * @param string $name 文件名称
     * @param null|string $attname 下载名称
     * @return string
     */
    public function url(string $name, ?string $attname = null): string
    {
        return ($this->config['cdn'] ?? $this->config['domain']) . "/{$this->delSuffix($name)}{$this->getSuffix($attname)}";
    }

    /**
     * 获取下载链接后缀
     * @param null|string $attname 下载名称
     * @return string
     */
    protected function getSuffix(?string $attname = null): string
    {
        if (is_string($attname) && strlen($attname) > 0) {
            return "?attname=" . urlencode($attname);
        }
        return '';
    }

    /**
     * 获取文件基础名称
     * @param string $name 文件名称
     * @return string
     */
    protected function delSuffix(string $name): string
    {
        if (strpos($name, '?') !== false) {
            return strstr($name, '?', true);
        }
        return $name;
    }

    /**
     * 获取文件上传地址
     * @return string
     */
    public function upload(): string
    {
        return "https://{$this->config['bucket']}.{$this->config['endpoint']}";
    }
}