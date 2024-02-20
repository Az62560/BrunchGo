<?php

namespace App\Controller;

use App\Classe\Mail;
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

                //envoi d'un mail à l'inscription
                $mail = new Mail();
                $content = 'Bonjour '.$user->getFirstname().'<br>Bienvenue sur Brunch Go, premier site de livraison de brunch à domicile développé par Ju².<br><br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque dui nisl, auctor ac felis sed, pharetra tempor justo. Nulla iaculis rhoncus hendrerit. Donec eu ex sapien. Duis lobortis leo at sapien ultricies luctus. Quisque vel euismod lectus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut quis magna at leo rutrum efficitur eu at ex. Mauris eleifend est at sem tristique ultricies. Ut mollis viverra risus ut venenatis.';
                $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur Brunch Go', $content);

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
