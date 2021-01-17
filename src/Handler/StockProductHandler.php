<?php

namespace App\Handler;

use App\Form\StockType;
use App\HandlerFactory\AbstractHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class StockProductHandler
 * @package App\Handler
 */
class StockProductHandler extends AbstractHandler
{
    private EntityManagerInterface $entityManager;
    
    private FlashBagInterface $flashBag;
    
    
    public function __construct(EntityManagerInterface $entityManager, FlashBagInterface $flashBag)
    {
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
    }
    
    /**
     * @inheritDoc
     */
    protected function process($data, array $options): void
    {
        $this->entityManager->flush();
        $this->flashBag->add(
            "success",
            "Le stock de votre produit ont été modifié avec succès."
        );
    }
    
    /**
     * @inheritDoc
     */
    protected function configure(OptionsResolver $resolver): void
    {
        $resolver->setDefault("form_type", StockType::class);
    }
}
