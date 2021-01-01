<?php

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class ForgottenPasswordTest
 * @package App\Tests
 */
class ForgottenPasswordTest extends WebTestCase
{

    /**
     * @param string $email
     * @dataProvider provideEmails
     */
    public function testSuccessfulForgottenPassword(string $email): void
    {
        $client = static::createClient();

        /**
         * @var RouterInterface $router
         */
        $router = $client->getContainer()->get('router');

        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_forgotten_password'));

        $form = $crawler->filter("form[name=forgotten_password]")
            ->form(
                [
                    "forgotten_password[email]" => $email,
                ]
            );

        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");

        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneByEmail($email);

        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate(
                'security_reset_password',
                [
                    'token' => $user->getForgottenPassword()->getToken(),
                ]
            )
        );

        $form = $crawler->filter("form[name=reset_password]")
            ->form(
                [
                    "reset_password[plainPassword]" => 'un2Tres!2',
                ]
            );
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function provideEmails(): \Generator
    {
        yield ['producer@gmail.com'];
        yield ['customer@gmail.com'];
    }



    /**
     * @param string $email
     * @param string $message
     * @dataProvider provideBadResetPassword
     */
    public function testBadRequestEmailForgottenPassword(string $email, string $message): void
    {
        $client = static::createClient();

        /**
         * @var RouterInterface $router
         */
        $router = $client->getContainer()->get('router');

        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_forgotten_password'));

        $form = $crawler->filter("form[name=forgotten_password]")
            ->form(
                [
                    "forgotten_password[email]" => $email,
                ]
            );

        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorTextContains("span.form-error-message", $message);
    }

    public function provideBadResetPassword(): \Generator
    {
        yield ['fail@gmail.com', "Oups! Cette adresse email n'existe pas encore dans notre bdd."];
    }


    /**
     * @param string $email
     * @param string $password
     * @param string $errorMessage
     * @dataProvider provideResetPassword
     */
    public function testFailBadFormatForgottenPassword(string $email, string $password, string $errorMessage): void
    {
        $client = static::createClient();

        /**
         * @var RouterInterface $router
         */
        $router = $client->getContainer()->get('router');

        $crawler = $client->request(Request::METHOD_GET, $router->generate('security_forgotten_password'));

        $form = $crawler->filter("form[name=forgotten_password]")
            ->form(
                [
                    "forgotten_password[email]" => $email,
                ]
            );

        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");

        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneByEmail($email);

        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate(
                'security_reset_password',
                [
                    'token' => $user->getForgottenPassword()->getToken(),
                ]
            )
        );

        $form = $crawler->filter("form[name=reset_password]")
            ->form(
                [
                    "reset_password[plainPassword]" => $password,
                ]
            );
        $client->submit($form);

        $this->assertSelectorTextContains("span.form-error-message", $errorMessage);
    }

    public function provideResetPassword(): \Generator
    {
        yield ['producer@gmail.com', "", "Cette valeur ne doit pas être vide."];
        yield ['producer@gmail.com', "ko", "Cette chaîne est trop courte. Elle doit avoir au minimum 3 caractères."];
        yield ['customer@gmail.com', "", "Cette valeur ne doit pas être vide."];
        yield ['customer@gmail.com', "ko", "Cette chaîne est trop courte. Elle doit avoir au minimum 3 caractères."];
    }
}
