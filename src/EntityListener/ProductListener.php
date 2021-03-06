<?php

namespace App\EntityListener;

use App\Entity\Product;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;

/**
 * Class ProductListener
 * @package App\EntityListener
 */
class ProductListener
{
    /**
     * @var Security
     */
    private Security $security;

    /**
     * @var string
     */
    private string $uploadAbsoluteDir;

    /**
     * @var string
     */
    private string $uploadWebDir;

    /**
     * ProductListener constructor.
     * @param Security $security
     * @param string $uploadAbsoluteDir
     * @param string $uploadWebDir
     */
    public function __construct(Security $security, string $uploadAbsoluteDir, string $uploadWebDir)
    {
        $this->security = $security;
        $this->uploadAbsoluteDir = $uploadAbsoluteDir;
        $this->uploadWebDir = $uploadWebDir;
    }


    /**
     * @param Product $product
     */
    public function prePersist(Product $product): void
    {
        if ($product->getFarm() !== null) {
            return;
        }
        $product->setFarm($this->security->getUser()->getFarm());
        $this->upload($product);
    }

    /**
     * @param Product $product
     */
    public function preUpdate(Product $product): void
    {
        $this->upload($product);
    }

    /**
     * @param Product $product
     */
    private function upload(Product $product): void
    {
        if ($product->getImage() === null || $product->getImage()->getFile() === null) {
            return; // @codeCoverageIgnore
        }

        $filename = Uuid::v4() . '.' . $product->getImage()->getFile()->getClientOriginalExtension();

        $product->getImage()->getFile()->move($this->uploadAbsoluteDir, $filename);

        $product->getImage()->setPath($this->uploadWebDir . $filename);
    }
}
