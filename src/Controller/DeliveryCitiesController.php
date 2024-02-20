<?php

namespace App\Controller;

use App\Entity\DeliveryCities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeliveryCitiesController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/delivery/cities', name: 'app_delivery_cities')]
    public function index(): Response
    {           
        $delivery = $this->entityManager->getRepository(DeliveryCities::class)->findAll();
        

        return $this->render('about_us/index.html.twig',[
            'delivery' => $delivery,
        ]);
    }
}
