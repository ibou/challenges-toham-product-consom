<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ForgottenPasswordInput;
use App\Entity\Customer;
use App\Entity\Producer;
use App\Entity\Product;
use App\Entity\User;
use App\Form\ForgottenPasswordType;
use App\Form\RegistrationType;
use App\Form\ResetPasswordType;
use App\Handler\CreateProductHandler;
use App\Handler\ForgottenPasswordHandler;
use App\Handler\RegistrationHandler;
use App\Handler\ResetPasswordHandler;
use App\HandlerFactory\HandlerFactoryInterface;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
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
     * @param HandlerFactoryInterface $handlerFactory
     * @return Response
     * @Route("/registration/{role}", name="security_registration")
     */
    public function registration(
        string $role,
        Request $request,
        HandlerFactoryInterface $handlerFactory
    ): Response {
        $user = Producer::ROLE === $role ? new Producer() : new Customer();
        
        $handler = $handlerFactory->createHandler(RegistrationHandler::class);
        
        if ($handler->handle($request, $user)) {
            return $this->redirectToRoute("index");
        }
        
        return $this->render("ui/security/registration.html.twig", [
            "form" => $handler->createView()
        ]);
    }
    
    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render("ui/security/login.html.twig", [
            "last_username" => $authenticationUtils->getLastUsername(),
            "error" => $authenticationUtils->getLastAuthenticationError()
        ]);
    }
    
    /**
     * @codeCoverageIgnore
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
    }
    
    /**
     * @param Request $request
     * @param HandlerFactoryInterface $handlerFactory
     * @return Response
     * @Route("/forgotten-password", name="security_forgotten_password")
     */
    public function forgottenPassword(Request $request, HandlerFactoryInterface $handlerFactory): Response
    {
        $forgottenPasswordInput = new ForgottenPasswordInput();
        
        $handler = $handlerFactory->createHandler(ForgottenPasswordHandler::class);
        
        if ($handler->handle($request, $forgottenPasswordInput)) {
            return $this->redirectToRoute("security_login");
        }
        
        return $this->render("ui/security/forgotten_password.html.twig", [
            "form" => $handler->createView()
        ]);
    }
    
    /**
     * @Route("/reset-password/{token}", name="security_reset_password")
     * @param string $token
     * @param Request $request
     * @param UserRepository $userRepository
     * @param HandlerFactoryInterface $handlerFactory
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function resetPassword(
        string $token,
        Request $request,
        UserRepository $userRepository,
        HandlerFactoryInterface $handlerFactory
    ): Response {
        if (!Uuid::isValid($token)
            || null === ($user = $userRepository->getUserByForgottenPasswordToken(Uuid::fromString($token)))
        ) {
            $this->addFlash("danger", "Cette demande d'oubli de mot de passe n'existe pas.");
            return $this->redirectToRoute("security_login");
        }
        
        $handler = $handlerFactory->createHandler(ResetPasswordHandler::class);
        
        if ($handler->handle($request, $user)) {
            return $this->redirectToRoute("security_login");
        }
        
        return $this->render("ui/security/reset_password.html.twig", [
            "form" => $handler->createView()
        ]);
    }
}
