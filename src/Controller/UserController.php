<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController
{

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return Response
     * @Route("/edit-password", name="user_edit_password")
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $form = $this->createForm(UserPasswordType::class, $this->getUser())
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getUser()->setPassword(
                $userPasswordEncoder->encodePassword($this->getUser(), $this->getUser()->getPlainPassword())
            );
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "Votre mot de pass a été modifié avec succès !");

            return $this->redirectToRoute("index");
        }

        return $this->render(
            "ui/user/edit_password.html.twig",
            [
                "form" => $form->createView(),
            ]
        );
    }
}
