<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{
    /**
     * @Route("/mon-compte/modifier_mot_de_passe", name="account_change_password")
     */
    public function index(Request $request,
                            UserPasswordEncoderInterface $passwordEncoder,
                            EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form->get('old_password')->getData();

            if ($passwordEncoder->isPasswordValid($user, $oldPassword)) {
                $newPassword = $form->get('new_password')->getData();
                $password = $passwordEncoder->encodePassword($user, $newPassword);

                $user->setPassword($password);

                $entityManager->flush();
            }
        }
        

        return $this->render('account/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
