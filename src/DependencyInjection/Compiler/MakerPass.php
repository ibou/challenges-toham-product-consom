<?php

namespace App\DependencyInjection\Compiler;

use App\Maker\HandlerMaker;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class HandlerPass
 * @package App\DependencyInjection\Compiler
 */
class MakerPass implements CompilerPassInterface
{
    
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition(HandlerMaker::class);
        
        $formTypes = [];
        
        $taggedServices = $container->findTaggedServiceIds("form.type", true);
        
        foreach (array_keys($taggedServices) as $serviceId) {
            $entityClassDetails = new ClassNameDetails($container->getDefinition($serviceId)->getClass(), "App\Form");
            $formTypes[$entityClassDetails->getRelativeName()] = $serviceId;
        }
        $definition->setArgument(0, $formTypes);
    }
}
