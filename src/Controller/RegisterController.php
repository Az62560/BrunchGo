<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {

        $notification = null;

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        //vérification si le formulaire est bien rempli et soumis
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            // dd($user);
            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            //vérification si l'email n'est pas connu de la bdd
            if (!$search_email) {
                $password = $encoder->hashPassword($user, $user->getPassword());
            
                $user->setPassword($password);

                //préparation des données pour l'envoi
                $this->entityManager->persist($user);
                //envoi dans la bdd
                $this->entityManager->flush();

                $notification = "Votre inscription s'est correctement déroulée. Vous pouvez dès à présent vous connecter à votre compte.";


                
            } else {

            $notification = 'Votre adresse mail est déjà connu, vous ne pouvez pas recréer un compte.';

            }  
        } 
        
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
