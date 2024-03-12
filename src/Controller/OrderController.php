<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Formules;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Entity\TimeSlots;
use App\Entity\WorkingDay;
use App\Form\OrderType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande', name: 'app_order')]
    public function index(Cart $cart, Request $request): Response
    {

        if (!$this->getUser()->getAddresses()->getValues()) {
            return $this->redirectToRoute('app_account_address_add');
        } 
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
            'workingDays' => $this->entityManager->getRepository(WorkingDay::class)->findByAvailable(1),
            'timeSlots' => $this->entityManager->getRepository(TimeSlots::class)->findByIsFree(1),
         
        ]);
        
        $session = $request->getSession();
        $selected_formule = $session->get('cart_formule');
        $selected_products = $session->get('cart_products', []);
        
        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->get(),
            'selected_formule' => $selected_formule,
            'selected_products' => $selected_products,
            
        ]);
    }

    #[Route('/commande/recapitulatif', name: 'app_order_recap')]
    public function add(Cart $cart, Request $request): Response
{
    $order = new Order();
    $workingDays = $this->entityManager->getRepository(WorkingDay::class)->findByAvailable(1);
    $timeSlots = $this->entityManager->getRepository(TimeSlots::class)->findByIsFree(1);
  
    $session = $request->getSession();
    
    $selected_formule = $session->get('cart_formule');
    $selected_products = $session->get('cart_products');
    
    $form = $this->createForm(OrderType::class, $order, [
        'user' => $this->getUser(),
        'workingDays' => $workingDays,
        'timeSlots' => $timeSlots,
        'selected_formule' => $selected_formule,
        'selected_products' => $selected_products,
    ]);
    
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $date = new DateTimeImmutable();
        $formData = $form->getData();
     
        // Récupérer les données du formulaire
        $deliveryAddress = $formData->getDeliveryAddress();
        $deliveryDay = $formData->getDeliveryDay();
        $deliveryHour = $formData->getDeliveryHour();
        $total = 0; // Initialiser le total à 0

        foreach ($selected_formule as $formuleArray) {
            $formule = $formuleArray[0]; // Récupérer l'objet Formule
            
            $formulePrice = $formule->getPrice(); // Obtenir le prix de la formule
            $total += $formulePrice; // Ajouter le prix de la formule au total
        }
        
        


        // Attribution des valeurs à l'objet Order
        $order->setDeliveryDay($deliveryDay);
        $order->setDeliveryHour($deliveryHour);
        $order->setDeliveryAddress(str_replace('[br]', ' ', $deliveryAddress));
        $order->setTotal($total);
        $order->setReference($date->format('dmY') . '-' . uniqid()); 
        $order->setUser($this->getUser());
        $order->setCreatedAt($date);
        $order->setState(0);
        // $order->setSelectedFormule($selected_formule);
        // Stockez les données de formule dans la session
        $session->set('selected_formule', $selected_formule);
        
        $this->entityManager->persist($order);
// dd($selected_formule);

foreach ($selected_formule as $formuleData) {
    // Créer une nouvelle instance d'OrderDetails pour chaque formule
    $orderDetails = new OrderDetails();
dd($selected_formule);
    // Récupérer les données de la formule
    $formule = $formuleData[0];
    $formuleName = $formule->getName();
    $formulePrice = $formule->getPrice();

    // Définir les données de la formule dans OrderDetails
    $orderDetails->setFormule($formuleName);
    $orderDetails->setPrice($formulePrice);

    // Initialiser un tableau pour stocker les noms des produits
    $productNames = [];

    // Récupérer les produits associés à cette formule
    $products = $formuleData[1];

    // Itérer sur chaque catégorie de produits
    foreach ($products as $category => $productCollection) {
        // Itérer sur chaque produit dans cette catégorie
        foreach ($productCollection as $product) {
            // Ajouter le nom du produit au tableau des noms de produits
            $productNames[] = $product->getName();
           
        }
    }

    // Construire une chaîne de noms de produits séparés par une virgule
    $productNamesString = implode(', ', $productNames);

    // Définir les produits dans OrderDetails
    $orderDetails->setProducts($productNamesString);

    // Associer OrderDetails à une commande
    $orderDetails->setMyOrder($order); // Supposons que $order contient l'objet Order associé à cette commande

    // Persister les OrderDetails pour chaque formule
    $this->entityManager->persist($orderDetails);

    // Ajouter cet OrderDetails à une collection pour pouvoir y accéder plus tard si nécessaire
    $order->addOrderDetail($orderDetails);
}
// dd($order);
        // Persist the order entity
        
        $this->entityManager->flush();
           
        return $this->render('order/add.html.twig', [
            'cart' => $cart->get(),
            'order' => $order,
            'reference' => $order->getReference(),
            'selected_formule' => $selected_formule,
            'selected_products' => $selected_products,
             
        ]);
    }
    
    // Redirection après la soumission réussie
    return $this->redirectToRoute('app_cart');
}
}
