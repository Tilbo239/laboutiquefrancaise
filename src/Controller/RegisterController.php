<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $user = new User();

        $form = $this->createForm(RegisterUserType::class, $user);

        $form->handleRequest($request);

        // Check if the form is submitted and valid
        // If the form is valid, you can process the data
        if( $form->isSubmitted() && $form->isValid()) {
            // dd($user);
            $em->persist($user);
            $em->flush();

            $this->addFlash(
            'success', 
            'Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.'
        );

            //redirect to a different page after successful registration
            return $this->redirectToRoute('app_login');

        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
