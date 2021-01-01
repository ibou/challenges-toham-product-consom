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

        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate(
                'security_registration',
                [
                    'role' => $role,
                ]
            )
        );

        $form = $crawler->filter("form[name=registration]")
            ->form(
                [
                    "registration[email]" => "email@gmail.com",
                    "registration[plainPassword]" => "dev123",
                    "registration[firstName]" => "firstName tester John",
                    "registration[lastName]" => "lastName tester W.",
                ]
            );

        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function provideRoles(): Generator
    {
        yield ['producer'];
        yield ['costumer'];
    }

    /**
     * @param string $role
     * @param array $formData
     * @param string $errorMessage
     * @dataProvider provideBadRequest
     */
    public function testBadRequest(string $role, array $formData, string $errorMessage): void
    {
        $client = static::createClient();

        /**
         * @var RouterInterface $router
         */
        $router = $client->getContainer()->get('router');

        $crawler = $client->request(
            Request::METHOD_GET,
            $router->generate(
                'security_registration',
                [
                    'role' => $role,
                ]
            )
        );

        $form = $crawler->filter("form[name=registration]")
            ->form($formData);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains("span.form-error-message", $errorMessage);
    }

    public function provideBadRequest(): Generator
    {
        foreach (["producer", "customer"] as $role) {
            yield [
                $role,
                [
                    "registration[email]" => "email@gmail",
                    "registration[plainPassword]" => "",
                    "registration[firstName]" => "firstName tester John",
                    "registration[lastName]" => "lastName tester W.",
                ],
                "Cette valeur n'est pas une adresse email valide.",
            ];
            yield [
                $role,
                [
                    "registration[email]" => "",
                    "registration[plainPassword]" => "pass",
                    "registration[firstName]" => "firstName tester John",
                    "registration[lastName]" => "lastName tester W.",
                ],
                "Cette valeur ne doit pas être vide.",
            ];

            yield [
                $role,
                [
                    "registration[email]" => "email@gmail.com",
                    "registration[plainPassword]" => "",
                    "registration[firstName]" => "firstName tester John",
                    "registration[lastName]" => "lastName tester W.",
                ],
                "Cette valeur ne doit pas être vide.",
            ];

            yield [
                $role,
                [
                    "registration[email]" => "email@gmail.com",
                    "registration[plainPassword]" => "fqfqdf",
                    "registration[firstName]" => "",
                    "registration[lastName]" => "lastName tester W.",
                ],
                "Cette valeur ne doit pas être vide.",
            ];
            yield [
                $role,
                [
                    "registration[email]" => "producer@gmail.com",
                    "registration[plainPassword]" => "fqfqdf",
                    "registration[firstName]" => "FQdqf",
                    "registration[lastName]" => "Faluu",
                ],
                "Il semble que cette adresse email soit déjà prise.",
            ];
            yield [
                $role,
                [
                    "registration[email]" => "email@gmail.com",
                    "registration[plainPassword]" => "fqfqdf",
                    "registration[firstName]" => "first name",
                    "registration[lastName]" => "",
                ],
                "Cette valeur ne doit pas être vide.",
            ];
        }
    }
}
