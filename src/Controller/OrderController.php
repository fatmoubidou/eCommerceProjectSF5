<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use App\Manager\CartManager;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/commande", name="order")
     */
    public function index(Request $request, CartManager $cart): Response
    {
        if (!$this->getUser()->getAddresses()->getValues()){
            return $this->redirectToRoute('account_add_address');
        }

        $form = $this->createForm(OrderType::class, null, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt(new DateTime('NOW'));
            $order->setcarrierName($form->getData()['carriers']->getName());
            $order->setcarrierPrice($form->getData()['carriers']->getPrice());
            $order->setDelivery($form->getData()['addresses']->getName());

            $this->em->persist($order);
            $this->em->flush();

            foreach($cart->getFull() as $product){
                $orderDetails = new OrderDetails();
                $orderDetails->setOrderClient($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['quantity'] * $product['product']->getPrice());
                $this->em->persist($orderDetails);
            }
            $this->em->flush();
        }

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull(),
        ]);
    }
}
