<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
                    ->add('index','detail')
                    ->remove('index', 'edit');

    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'N° de commande'),
            DateField::new('createdAt', 'Date de création'),
            TextField::new('user.fullname', 'Client'),
            MoneyField::new('totalPrice', 'Total')->setCurrency('EUR'),
            TextField::new('carrierName', 'Transporteur'),
            BooleanField::new('status', 'Statut')
        ];
    }
    
}
