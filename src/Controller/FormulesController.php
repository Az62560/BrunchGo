<?php

namespace App\Controller;

use App\Entity\Formules;
use App\Form\PersonnalisationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class FormulesController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

#[Route('/nos-formules', name: 'app_formules')]
    
    public function index(): Response
    {
        $formules = $this->entityManager->getRepository(Formules::class)->findAll();

        return $this->render('formules/index.html.twig', [
            'formules' =>$formules
        ]);
    }

    #[Route("/formule/{slug}", name: 'formule')]

    public function show($slug): Response
    {   
       
        $formule = $this->entityManager->getRepository(Formules::class)->findOneBySlug($slug);
            if (!$formule) {
                return $this->redirectToRoute('app_formules');
                
        }

        return $this->render('formules/show.html.twig', [
            'formule' => $formule,
            
        ]);
    }
    public function perso()
    {
        $perso = new FormulesController();
        $form = $this->createForm(PersonnalisationType::class, $perso);
        return $this->render('formules/show/html/twig',[
            'form' =>$form->createView()
        ]);
    }
}
