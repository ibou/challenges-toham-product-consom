<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ForgottenPasswordInput;
use App\Entity\Customer;
use App\Entity\Producer;
use App\Entity\User;
use App\Form\ForgottenPasswordType;
use App\Form\RegistrationType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Uid\Uuid;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends AbstractController
{

    /**
     * @param string $role
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return Response
     * @Route("/registration/{role}", name="security_registration")
     */
    public function registration(
        string $role,
        Request $request,
        UserPasswordEncoderInterface $userPasswordEncoder
    ): Response {
        $user = Producer::ROLE === $role ? new Producer() : new Customer();
        $form = $this->createForm(
            RegistrationType::class,
            $user,
            [
                "validation_groups" => ["Default", "password"],
            ]
        )->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordEncoder->encodePassword($user, $user->getPlainPassword())
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Votre inscription est validée avec succès !');

            return $this->redirectToRoute('index');
        }

        return $this->render(
            'ui/security/registration.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/login", name="security_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render(
            'ui/security/login.html.twig',
            [
                'last_username' => $authenticationUtils->getLastUsername(),
                'error' => $authenticationUtils->getLastAuthenticationError(),
            ]
        );
    }

    /**
     * @codeCoverageIgnore
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
    }

    /**
     * @Route("/forgotten-password", name="security_forgotten_password")
     * @param Request $request
     * @param UserRepository $userRepository
     * @param MailerInterface $mailer
     * @return Response
     */
    public function forgottenPassword(
        Request $request,
        UserRepository $userRepository,
        MailerInterface $mailer
    ): Response {
        $forgottenPasswordInput = new ForgottenPasswordInput();
        $form = $this->createForm(ForgottenPasswordType::class, $forgottenPasswordInput)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneByEmail($forgottenPasswordInput->getEmail());
            /**@var User $user */
            $user->hasForgotHisPassword();
            $this->getDoctrine()->getManager()->flush();


            $email = (new TemplatedEmail())
                ->to("hello@productconsumer.com")
                ->from("ibrah@gmail.com")
                ->context(["forgottenPassword" => $user->getForgottenPassword()])
                ->htmlTemplate("emails/forgotten_password.html.twig");

            $mailer->send($email);


            $this->addFlash(
                "success",
                "Votre demande d'oubli de mot de passe a bien été enregistrée. 
                Vous allez recevoir un email pour réinitialiser votre mot de passe"
            );

            return $this->redirectToRoute('security_login');
        }

        return $this->render(
            'ui/security/forgotten_password.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/reset-password/{token}", name="security_reset_password")
     * @param string $token
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function resetPassword(
        string $token,
        Request $request,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $userPasswordEncoder
    ): Response {
        /** @var User $user */
        $user = $userRepository->getUserByForgottenPass(Uuid::fromString($token));
        if (null === $user) {
            $this->addFlash("danger", "Cette demande d'oubli de mot de passe n'existe pas.");

            return $this->redirectToRoute("security_login");
        }

        $form = $this->createForm(
            ResetPasswordType::class,
            $user,
            [
                "validation_groups" => ["password"],
            ]
        )->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordEncoder->encodePassword($user, $user->getPlainPassword())
            );
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                "success",
                "Votre mot de passe a été modifié avec succès."
            );

            return $this->redirectToRoute("security_login");
        }

        return $this->render(
            "ui/security/reset_password.html.twig",
            [
                "form" => $form->createView(),
            ]
        );
    }
}
