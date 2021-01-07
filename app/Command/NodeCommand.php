<?php
/**
 *  +-------------------------------------
 *  | MADE IN RETURN
 *  |-------------------------------------
 *  | Time: 2020/11/20
 *  | Author: CRAYOON <so.wo@foxmail.com>
 *  +--------------------------------------
 */

declare(strict_types=1);


namespace App\Command;


use App\Annotation\Node;
use App\Model\SysNode;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Di\Aop\AspectManager;
use Hyperf\HttpServer\MiddlewareManager;
use Hyperf\HttpServer\Router\DispatcherFactory;
use Hyperf\HttpServer\Router\Handler;
use Hyperf\HttpServer\Router\RouteCollector;
use Hyperf\Utils\Str;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 服务节点更新
 * Class NodeCommand
 * @Command()
 * @package App\Command
 */
class NodeCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ConfigInterface
     */
    private $config;

    public function __construct(ContainerInterface $container, ConfigInterface $config)
    {
        parent::__construct('ser:node');
        $this->container = $container;
        $this->config = $config;
    }

    public function handle()
    {
        $factory = $this->container->get(DispatcherFactory::class);
        //只支持http应用
        $router = $factory->getRouter("http");
        $this->show(
            $this->analyzeRouter("http", $router, null),
            $this->output
        );
    }

    protected function configure()
    {
        $this->setDescription('更新节点');
    }

    protected function analyzeRouter(string $server, RouteCollector $router, ?string $path)
    {
        $data = [];
        [$staticRouters, $variableRouters] = $router->getData();
        foreach ($staticRouters as $method => $items) {
            foreach ($items as $handler) {
                $this->analyzeHandler($data, $server, $method, $path, $handler);
            }
        }
        foreach ($variableRouters as $method => $items) {
            foreach ($items as $item) {
                if (is_array($item['routeMap'] ?? false)) {
                    foreach ($item['routeMap'] as $routeMap) {
                        $this->analyzeHandler($data, $server, $method, $path, $routeMap[0]);
                    }
                }
            }
        }
        return $data;
    }

    protected function analyzeHandler(array &$data, string $serverName, string $method, ?string $path, Handler $handler)
    {
        $uri = trim($handler->route, "/");
        if (!is_null($path) && !Str::contains($uri, $path)) {
            return;
        }
        if (is_array($handler->callback)) {
            $action = $handler->callback[0] . '::' . $handler->callback[1];
        } elseif (is_string($handler->callback)) {
            $action = $handler->callback;
        } elseif (is_callable($handler->callback)) {
            $action = 'Closure';
        } else {
            $action = (string)$handler->callback;
        }
        if (!isset($data[$uri])) {
            list($class, $fun) = [null, null];
            if (strpos($action, "::")) {
                list($class, $fun) = explode("::", $action);
            } else if (strpos($action, "@")) {
                list($class, $fun) = explode("@", $action);
            }
            //只兼容 上述两种情况
            if ($class && $fun) {
                $annotationCollector = AnnotationCollector::getClassMethodAnnotation($class, $fun);
                if (isset($annotationCollector[Node::class])) {
                    $data[$uri] = [
                        "node" => $uri,
                        "title" => $annotationCollector[Node::class]->value ?? ""
                    ];
                }
            }
        }
    }

    private function show(array $data, OutputInterface $output)
    {
//        $output->writeln(json_encode($rows));
        //更新节点
        $res = SysNode::updateNode($data);
        if ($res === true) {
            $output->writeln("<info>successful</info>");
        } else {
            $output->writeln("<error>$res</error>");
        }

    }
}