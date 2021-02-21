<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'form.address.name',
                'required' => true
                ])
            ->add('company', TextType::class,[
                'label' => 'form.address.company',
                'required' => false
                ])
            ->add('firstname', TextType::class,[
                'label' => 'form.address.firstname',
                'required' => true
                ])
            ->add('lastname', TextType::class,[
                'label' => 'form.address.lastname',
                'required' => true
                ])
            ->add('address', TextType::class,[
                'label' => 'form.address.address',
                'required' => true
                ])
            ->add('zipcode', TextType::class,[
                'label' => 'form.address.zipcode',
                'required' => true
                ])
            ->add('city', TextType::class,[
                'label' => 'form.address.city',
                'required' => true
                ])
            ->add('country', CountryType::class,[
                'label' => 'form.address.country',
                'required' => true
                ])
            ->add('phone', TelType::class,[
                'label' => 'form.address.phone',
                'required' => true
                ])
            ->add('submit', SubmitType::class, [
                'label' => "form.address.valid",
                'attr' => [
                    'class' => 'btn-block btn-primary'
                ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
