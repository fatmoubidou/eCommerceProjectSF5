<?php

namespace App\Controller;

use App\Entity\Product;
use App\Manager\CartManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/cart", name="cart")
     */
    public function index(CartManager $cart): Response
    {
        
        $cartDetails = [];

        foreach($cart->get() as $id => $quantity) {
            $cartDetails[] = [
                'product' => $this->em->getRepository(Product::class)->findOneById($id),
                'quantity' => $quantity
            ];
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cartDetails
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="add_to_cart")
     */
    public function add($id, CartManager $cart): Response
    {
        $cart->add($id);

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/remove", name="remove_cart")
     */
    public function revove(CartManager $cart): Response
    {
        $cart->remove();

        return $this->redirectToRoute('products');
    }
}
