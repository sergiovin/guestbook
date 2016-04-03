<?php

namespace InfrastructureBundle;

use InfrastructureBundle\DependencyInjection\Compiler\CommandHandlerCompilerPass;
use InfrastructureBundle\DependencyInjection\Compiler\DoctrineEntityListenerPass;
use InfrastructureBundle\DependencyInjection\Compiler\EventDispatcherCompilerPass;
use InfrastructureBundle\DependencyInjection\Compiler\RepositoryManagerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\DBAL\Types\Type;

class InfrastructureBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CommandHandlerCompilerPass());
        $container->addCompilerPass(new EventDispatcherCompilerPass());
        $container->addCompilerPass(new DoctrineEntityListenerPass());
        $container->addCompilerPass(new RepositoryManagerPass());
    }

    public function boot()
    {

    }
}
