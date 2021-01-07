<?php

declare(strict_types=1);

namespace App\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;
use Swoole\Process;

/**
 * @Command
 */
class SerCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('ser:reload');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Hyperf Reload Command');
    }

    public function handle()
    {
        $this->stop();
    }

    /**
     * 获取主进程PID
     * @access protected
     * @return int
     */
    protected function getMasterPid()
    {
        $config = $this->container->get(ConfigInterface::class);
        $file = $config->get('server.settings.pid_file', BASE_PATH . '/runtime/hyperf.pid');
        if (is_file($file)) {
            $masterPid = (int)file_get_contents($file);
        } else {
            $masterPid = 0;
        }
        return $masterPid;
    }

    /**
     * 判断PID是否在运行
     * @access protected
     * @param  int $pid
     * @return bool
     */
    protected function isRunning($pid)
    {
        if (empty($pid)) {
            return false;
        }

        return Process::kill($pid, 0);
    }

    /**
     * 停止
     * @return bool
     */
    protected function stop()
    {
        $pid = $this->getMasterPid();

        if (!$this->isRunning($pid)) {
            $this->line('没有程序在运行', 'error');
            return false;
        }
        $this->line('> 正在停止程序...', 'info');

        Process::kill($pid, SIGTERM);
        $this->removePid();
        $this->line('> success...', 'info');
        return true;
    }

    /**
     * 删除PID文件
     * @access protected
     * @return void
     */
    protected function removePid()
    {
        $config = $this->container->get(ConfigInterface::class);
        $masterPid = $config->get('server.settings.pid_file', BASE_PATH . '/runtime/hyperf.pid');

        if (is_file($masterPid)) {
            unlink($masterPid);
        }
    }
}
