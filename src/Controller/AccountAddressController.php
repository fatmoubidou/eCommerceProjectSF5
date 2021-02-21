<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/mon-compte/gerer_mes-adresses", name="account_address")
     */
    public function index(): Response
    {
    
        return $this->render('account/address.html.twig', [
            'controller_name' => 'AccountAddressController',
        ]);
    }

    /**
     * @Route("/mon-compte/ajouter_une-adresse", name="account_add_address")
     */
    public function add(Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isSubmitted()){
            $address->setUser($this->getUser());

            $this->em->persist($address);
            $this->em->flush();

            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/address_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/mon-compte/modifier-une-adresse/{id}", name="account_edit_address")
     */
    public function edit(Request $request, $id): Response
    {
        $address = $this->em->getRepository(Address::class)->findOneById($id);

        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_address');  
        }

        $form = $this->createForm(AddressType::class, $address);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isSubmitted()){

                $this->em->flush();

                return $this->redirectToRoute('account_address');
            }

            return $this->render('account/address_add.html.twig', [
                'form' => $form->createView(),
            ]);
    }

        /**
     * @Route("/mon-compte/supprimer-une-adresse/{id}", name="account_delete_address")
     */
    public function delete(Request $request, Address $id): Response
    {
        $address = $this->em->getRepository(Address::class)->findOneById($id);

        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_address');  
        }

        $this->em->remove($address);
        $this->em->flush();
        
        return $this->redirectToRoute('account_address');  
    }
}
