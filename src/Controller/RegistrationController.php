<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     *?Méthode permettant de créer notre compte
     *  
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        // UserPasswordHasherInterface : interface permettant d'encoder le mot de passe

        //TODO: pour insérer un nouvel user, on doit instancier la classe user
        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);//TODO $form lié à la table $userser

        $form->handleRequest($request);//TODO correspondance des champs

        if ($form->isSubmitted() && $form->isValid()) {
            
            // On fait appel à l'objet $encoder afin de hacher le mot de passe 
            // hashPassword() : méthode issue de UserPasswordHasherInterface permettant de créer une clé de hachage pour le mot de passe
            $hash = $encoder->hashPassword($user, $user->getPassword());

            dump($hash);

            $user->setPassword($hash);

            $entityManager = $this->getDoctrine()->getManager();//! équivaut à l'injection de dépendance, ici c'est 1 appel direct
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
