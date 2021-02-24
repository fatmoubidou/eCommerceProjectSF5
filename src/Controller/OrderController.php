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
        

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull(),
        ]);
    }


        /**
     * @Route("/commande/recapitulatif", name="order_summary", methods="POST")
     */
    public function add(Request $request, CartManager $cart): Response
    {
        $form = $this->createForm(OrderType::class, null, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $carrier = $form->get('carriers')->getData();
            $address = $form->get('addresses')->getData();
            $delivery = $address->getFirstname().' '.$address->getLastname().'<br/>';
            $delivery .= (!empty($address->getCompany()))??$address->getCompany().'<br/>';
            $delivery .= $address->getAddress().'<br/>';
            $delivery .= $address->getZipcode().' '.$address->getCity().' '.$address->getCountry();

            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt(new DateTime('NOW'));
            $order->setcarrierName($carrier->getName());
            $order->setcarrierPrice($carrier->getPrice());
            $order->setDelivery($delivery);
            $order->setStatus(0);
            $this->em->persist($order);

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

            return $this->render('order/add.html.twig', [
                'cart' => $cart->getFull(),
                'carrier' => $carrier,
                'delivery' => $delivery
            ]);
        }

        return $this->redirectToRoute('cart');
    }
}
