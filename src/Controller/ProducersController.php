<?php

namespace App\Controller;

use App\Entity\Producers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProducersController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/nos-partenaires', name: 'app_producers')]
    public function index(): Response
    {
        $producers = $this->entityManager->getRepository(Producers::class)->findAll();
        
        // dd($producers);
        return $this->render('producers/index.html.twig', [
            'producers' => $producers,
            
        ]);
    }
}
