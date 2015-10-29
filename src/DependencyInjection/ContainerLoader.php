<?php

namespace Accompli\DependencyInjection;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * ContainerLoader.
 *
 * @author Niels Nijens <nijens.niels@gmail.com>
 */
class ContainerLoader
{
    /**
     * The container instance.
     *
     * @var ContainerBuilder
     */
    private $container;

    /**
     * Constructs a new ContainerLoader instance.
     *
     * @param ContainerBuilder $container
     */
    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    /**
     * Loads the services configured in the configuration into the dependency injection container.
     */
    public function load()
    {
        $this->container->register('configuration', 'Accompli\Configuration\Configuration')->addMethodCall('load', array('%configuration.file%'));

        $configuration = $this->container->get('configuration');

        $this->container->register('event_dispatcher', 'Symfony\Component\EventDispatcher\EventDispatcher');
        $this->container->register('logger', 'Symfony\Component\Console\Logger\ConsoleLogger')->addArgument('%console.output_interface%');

        foreach ($this->container->getDefinitions() as $definition) {
            $interfaces = class_implements($definition->getClass());
            foreach ($interfaces as $interface) {
                if (substr($interface, -14) === 'AwareInterface') {
                    $camelCasedServiceId = substr($interface, strrpos($interface, '\\') + 1, -14);
                    $serviceId = Container::underscore($camelCasedServiceId);

                    if ($this->container->hasDefinition($serviceId)) {
                        $setterMethod = 'set'.$camelCasedServiceId;

                        $definition->addMethodCall($setterMethod, array(new Reference($serviceId)));
                    }
                }
            }
        }
    }
}
