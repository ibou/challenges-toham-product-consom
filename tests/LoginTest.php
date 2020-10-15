<?php

namespace App\Tests;

use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class LoginTest
 * @package App\Tests
 */
class LoginTest extends WebTestCase
{

    /**
     * @param string $email
     * @dataProvider provideEmails
     */
    public function testSuccessfulLogin(string $email): void
    {
        $client = static::createClient();

        /**
         * @var RouterInterface $router
         */
        $router = $client->getContainer()->get('router');

        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login', [
            'email' => $email,
            'password' => 'dev',
        ]));

        $form = $crawler->filter("form[name=login]")
            ->form([
                "email" => $email,
                "password" => "dev",
            ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function provideEmails(): Generator
    {
        yield ['producer@gmail.com'];
        yield ['customer@gmail.com'];
    }
}
