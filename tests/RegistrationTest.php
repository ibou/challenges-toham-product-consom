<?php

namespace App\Tests;

use Generator;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class RegistrationTest
 * @package App\Tests
 */
class RegistrationTest extends WebTestCase
{

    /**
     * @param string $role
     * @dataProvider provideRoles
     */
    public function testSuccessfulRegistration(string $role): void
    {
        $client = static::createClient();

        /**
         * @var RouterInterface $router
         */
        $router = $client->getContainer()->get('router');

        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_registration', [
            'role' => $role
        ]));

        $form = $crawler->filter("form[name=registration]")
            ->form([
                "registration[email]" => "email@email.com",
                "registration[plainPassword]" => "dev123",
                "registration[firstName]" => "firstName tester John",
                "registration[lastName]" => "lastName tester W."
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
