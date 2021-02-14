<?php

namespace App\Controller;


use App\Manager\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(CartManager $cart): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getFull()
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
     * @Route("/cart/decrease/{id}", name="decrease_to_cart")
     */
    public function decrease($id, CartManager $cart): Response
    {
        $cart->decrease($id);

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/delete/{id}", name="delete_product_to_cart")
     */
    public function delete($id, CartManager $cart): Response
    {
        $cart->delete($id);

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/remove", name="remove_cart")
     */
    public function remove(CartManager $cart): Response
    {
        $cart->remove();

        return $this->redirectToRoute('products');
    }
}
