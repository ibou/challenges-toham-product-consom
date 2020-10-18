<?php


namespace App\EntityListener;

use App\Entity\Product;
use Symfony\Component\Security\Core\Security;

class ProductListener
{
    private Security $security;

    /**
     * ProductListener constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param Product $product
     */
    public function prePersist(Product $product): void
    {
        //$product->setFarm($this->security->getUser()->getFarm());
    }
}
