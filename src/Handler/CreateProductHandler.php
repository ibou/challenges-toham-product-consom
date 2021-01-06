<?php


namespace App\Handler;

use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * Class CreateProductHandler
 * @package App\Handler
 */
class CreateProductHandler extends AbstractHandler
{
    private EntityManagerInterface $entityManager;
    
    private FlashBagInterface $flashBag;
    

    public function __construct(EntityManagerInterface $entityManager, FlashBagInterface $flashBag)
    {
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
    }
    
    
    public function getFormType(): string
    {
        return ProductType::class;
    }
    
    public function process($data): void
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        $this->flashBag->add(
            "success",
            "Votre produit a été créé avec succès."
        );
    }
}