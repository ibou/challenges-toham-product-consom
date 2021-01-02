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

        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));

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

    public function testInvalidCredentials(): void
    {
        $client = static::createClient();

        /**
         * @var RouterInterface $router
         */
        $router = $client->getContainer()->get('router');

        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));

        $form = $crawler->filter("form[name=login]")
            ->form([
                "email" => 'producer@gmail.com',
                "password" => "fail",
            ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains("div.alert-danger", "Identifiants invalides.");
    }

    public function testInvalidCredentialsLikeEmail(): void
    {
        $client = static::createClient();

        /**
         * @var RouterInterface $router
         */
        $router = $client->getContainer()->get('router');

        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_login'));

        $form = $crawler->filter("form[name=login]")
            ->form([
                "email" => 'fail@gmail.com',
                "password" => "fail",
            ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains("div.alert-danger", "Email could not be found.");
    }

    /**
     * @param string $email
     * @dataProvider provideEmails
     */
    public function testInvalidCsrfTokenLogin(string $email): void
    {
        $client = static::createClient();

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");

        $crawler = $client->request(Request::METHOD_GET, $router->generate("security_login"));

        $form = $crawler->filter("form[name=login]")->form([
            "_csrf_token" => "fail",
            "email" => $email,
            "password" => "password"
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertSelectorTextContains("div.alert-danger", 'Jeton CSRF invalide.');
    }
}
