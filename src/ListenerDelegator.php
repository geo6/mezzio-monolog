<?php

namespace Geo6\Expressive\Monolog;

use Geo6\Expressive\Monolog\Listener\Listener;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\DelegatorFactoryInterface;

class ListenerDelegator implements DelegatorFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        $listener = $container->get(Listener::class);

        /** @var \Zend\Stratigility\Middleware\ErrorHandler $errorHandler */
        $errorHandler = $callback();
        $errorHandler->attachListener($listener);

        return $errorHandler;
    }
}
