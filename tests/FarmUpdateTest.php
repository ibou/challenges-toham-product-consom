<?php

namespace App\Tests;

use Generator;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class FarmUpdateTest
 * @package App\Tests
 */
class FarmUpdateTest extends WebTestCase
{

    use AuthenticationTrait;

    public function testFarmUpdateTest(): void
    {
        $client = static::createAuthenticatedClient("producer@gmail.com");

        /**
         * @var RouterInterface $router
         */
        $router = $client->getContainer()->get('router');

        $crawler = $client->request(Request::METHOD_GET, $router->generate('farm_update'));

        $form = $crawler->filter("form[name=farm]")
            ->form([
                "farm[name]" => "Exploitation",
                "farm[description]" => "Descrition test de la ferme",
            ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function provideRoles(): Generator
    {
        yield ['producer'];
        yield ['costumer'];
    }
}
