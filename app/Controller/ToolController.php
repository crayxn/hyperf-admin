<?php
/**
 *  +-------------------------------------
 *  | MADE IN RETURN
 *  |-------------------------------------
 *  | Time: 2020/11/26
 *  | Author: CRAYOON <so.wo@foxmail.com>
 *  +--------------------------------------
 */

declare(strict_types=1);


namespace App\Controller;


use App\Common\Storage\Storage;

class ToolController extends BaseController
{
    public function favicon()
    {
        return $this->response->redirect("https://crmtest2018.oss-cn-hangzhou.aliyuncs.com/p_apm_t/avatar/2020/11/ar2gy-apw17-001.ico");
    }

    public function icon()
    {
        return $this->view([
            "title" => "图标选择",
            "field" => $this->request->query("field", "icon")
        ]);
    }

    public function upload()
    {
        return $this->render->render('tool.upload', [
            "exts" => json_encode([
                'bmp' => 'image/bmp',
                'gif' => 'image/gif',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'jpe' => 'image/jpeg',
                'txt' => 'text/plain',
                'text' => 'text/plain',
                'webm' => 'video/webm',
                'f4v' => 'video/x-f4v',
                'flv' => 'video/x-flv',
                'm4v' => 'video/x-m4v',
                'wmv' => 'video/x-ms-wmv',
                'wmx' => 'video/x-ms-wmx',
                'avi' => 'video/x-msvideo',
                'movie' => 'video/x-sgi-movie',
                'mp4' => 'video/mp4',
                'mp4v' => 'video/mp4',
                'mpg4' => 'video/mp4',
                'mpeg' => 'video/mpeg'
            ])
        ])->withHeader("content-Type", "application/x-javascript");
    }

    /**
     * 检查文件 并获取 上传参数
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Hyperf\Di\Exception\Exception
     */
    public function upload_state()
    {
        $uptype = "alioss";
        $file = $this->request->input('xkey');
        $file = date("Ym/") . str_replace("/", "", $file);
        $name = $this->request->input('name', null);
        $data = ['uptype' => $uptype, 'xkey' => $file, 'safe' => 0];
        $storage = Storage::init($uptype);
        if ($info = $storage->findFile($file, $name)) {
            $data['url'] = $info['url'];
            return $this->success('文件已经上传', $data, 200);
        } else {
            $data = array_merge($data, $storage->getUploadParam($file, 3600, $name));
        }
        return $this->success('获取授权参数', $data, 404);
    }
}