<?php

namespace App\Tests;

use App\Entity\Farm;
use App\Entity\Producer;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class ProductTest
 * @package App\Tests
 */
class ProductTest extends WebTestCase
{
    use AuthenticationTrait;
    
    public function testSuccessfulProductList(): void
    {
        $client = static::createAuthenticatedClient("producer@gmail.com");
        
        /**
         * @var RouterInterface $router
         */
        $router = $client->getContainer()->get('router');
        
        $client->request(Request::METHOD_GET, $router->generate('product_index'));
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    public function testSuccessfulProductUpdate(): void
    {
        $client = static::createAuthenticatedClient("producer@gmail.com");
        
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        
        $producer = $entityManager->getRepository(Producer::class)->findOneByEmail("producer@gmail.com");
        
        $farm = $entityManager->getRepository(Farm::class)->findOneByProducer($producer);
        
        $product = $entityManager->getRepository(Product::class)->findOneByFarm($farm);
        
        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate(
                "product_update",
                [
                    "id" => (string)$product->getId(),
                ]
            )
        );
        
        $form = $crawler->filter("form[name=product]")->form(
            [
                "product[name]" => "Produit",
                "product[description]" => "Description",
                "product[price][unitPrice]" => 100,
                "product[price][vat]" => 2.1,
            ]
        );
        
        $client->submit($form);
        
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
    
    public function testSuccessfulProductDelete(): void
    {
        $client = static::createAuthenticatedClient("producer@gmail.com");
        
        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");
        
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");
        
        $producer = $entityManager->getRepository(Producer::class)->findOneByEmail("producer@gmail.com");
        
        $farm = $entityManager->getRepository(Farm::class)->findOneByProducer($producer);
        
        $product = $entityManager->getRepository(Product::class)->findOneByFarm($farm);
        
        $client->request(
            Request::METHOD_GET,
            $router->generate(
                "product_delete",
                [
                    "id" => (string)$product->getId(),
                ]
            )
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
    
    
    public function testSuccessfulProductCreate(): void
    {
        $client = static::createAuthenticatedClient("producer@gmail.com");
        
        /**
         * @var RouterInterface $router
         */
        $router = $client->getContainer()->get('router');
        
        $crawler = $client->request(Request::METHOD_GET, $router->generate('product_create'));
        
        $form = $crawler->filter("form[name=product]")
            ->form(
                [
                    "product[name]" => "lait",
                    "product[description]" => "Du lait ferment, pure...",
                    "product[price][unitPrice]" => 101,
                    "product[price][vat]" => 2.1,
                ]
            );
        
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}
