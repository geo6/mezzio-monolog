<?php

namespace Geo6\Expressive\Monolog\Listener;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ListenerFactory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');

        $monolog = $config['monolog'] ?? [];
        $debug = $config['debug'] ?? false;

        return new Listener($monolog, $debug);
    }
}
