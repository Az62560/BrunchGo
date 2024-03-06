<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Formules;
use App\Entity\Producers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $formules = $this->entityManager->getRepository(Formules::class)->findByIsBest(1);

        $producers = $this->entityManager->getRepository(Producers::class)->findAll();

        //method pour mélanger les producteurs dans un tableau.
        shuffle($producers);
        //method pour afficher trois résultat du tableau.
        $randomP = array_slice($producers, 0, 3);


        return $this->render('home/index.html.twig', [
            'formules' => $formules,
            'randomP' => $randomP,
            
        ]);
    }
}
