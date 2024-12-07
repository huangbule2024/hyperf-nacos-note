<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Listener;

use Hyperf\Context\ApplicationContext;
use Hyperf\Database\ConnectionResolverInterface;
use Hyperf\Database\Events\QueryExecuted;
use Hyperf\Database\Model\Register;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\OnPipeMessage;
use Hyperf\Process\Event\PipeMessage as UserProcessPipeMessage;
use Hyperf\Redis\Pool\PoolFactory;
use Hyperf\Redis\Redis;
use Psr\Container\ContainerInterface;

/**
 * nacos修改触发事件
 * @author hbl
 * @date 2024/12/5
 */
#[Listener]
class NacosConfigChangedListener implements ListenerInterface
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function listen(): array
    {
        return [
            OnPipeMessage::class,
            UserProcessPipeMessage::class,
        ];
    }

    /**
     * @param QueryExecuted $event
     */
    public function process(object $event): void
    {
        var_dump("pull nacos config changed");
        //重置mysql
        $container = ApplicationContext::getContainer();
        $container->unbind(\Hyperf\DbConnection\Pool\PoolFactory::class);
        $container->unbind(ConnectionResolverInterface::class);
        Register::setConnectionResolver(
            $container->get(ConnectionResolverInterface::class)
        );

        //重置redis
        $this->container->unbind(PoolFactory::class);
        $this->container->unbind(Redis::class);

    }
}
