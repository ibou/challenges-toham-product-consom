<?php

namespace App\Tests;

use App\Entity\Farm;
use Doctrine\ORM\EntityManagerInterface;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Class UserTest
 * @package App\Tests
 */
class UserTest extends WebTestCase
{
    use AuthenticationTrait;


    public function testSuccessfulEditPassword(): void
    {
        $client = static::createAuthenticatedClient("customer@gmail.com");

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");


        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate(
                "user_edit_password"
            )
        );

        $form = $crawler->filter("form[name=user_password]")->form(
            [
                "user_password[currentPassword]" => "dev",
                "user_password[plainPassword][first]" => "devs",
                "user_password[plainPassword][second]" => "devs",
            ]
        );
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    /**
     * @param array $formData
     * @param string $errorMessage
     * @dataProvider provideBadRequests
     */
    public function testBadRequestPassword(array $formData, string $errorMessage): void
    {
        $client = static::createAuthenticatedClient("customer@gmail.com");

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");


        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate(
                "user_edit_password"
            )
        );

        $form = $crawler->filter("form[name=user_password]")->form($formData);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorTextContains("span.form-error-message", $errorMessage);
    }

    /**
     * @return Generator
     */
    public function provideBadRequests(): Generator
    {
        yield [
            [
                "user_password[currentPassword]" => "dev",
                "user_password[plainPassword][first]" => "devss",
                "user_password[plainPassword][second]" => "devs",
            ],
            "Le mot de passe et sa confirmation ne sont pas similaires.",
        ];
        yield [
            [
                "user_password[currentPassword]" => "dev",
                "user_password[plainPassword][first]" => "",
                "user_password[plainPassword][second]" => "",
            ],
            "Cette valeur ne doit pas être vide.",
        ];
        yield [
            [
                "user_password[currentPassword]" => "",
                "user_password[plainPassword][first]" => "devss",
                "user_password[plainPassword][second]" => "devs",
            ],
            "Cette valeur doit être le mot de passe actuel de l'utilisateur.",
        ];

        yield [
            [
                "user_password[currentPassword]" => "ddd",
                "user_password[plainPassword][first]" => "devss",
                "user_password[plainPassword][second]" => "devs",
            ],
            "Cette valeur doit être le mot de passe actuel de l'utilisateur.",
        ];

        yield [
            [
                "user_password[currentPassword]" => "dev",
                "user_password[plainPassword][first]" => "d",
                "user_password[plainPassword][second]" => "d",
            ],
            "Cette chaîne est trop courte. Elle doit avoir au minimum 3 caractères.",
        ];
    }
}
