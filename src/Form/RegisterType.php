<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class,[
                'label' => 'form.register.firstname',
                'required' => true
                ])
            ->add('lastname', TextType::class, [
                'label' => 'form.register.lastname',
                'required' => true
                ])
            ->add('email', EmailType::class, [
                'label' => 'form.register.email',
                'required' => true
                ])
            ->add('password', RepeatedType::class, [
                'required' => true,
                'invalid_message' => 'Le mot de passe et sa confirmation doivent être identique',
                'type' => PasswordType::class,
                'first_options' => ['label' => 'form.register.password'],
                'second_options' => ['label' => 'form.register.confirmPassword']
                ])

            ->add('submit', SubmitType::class, [
                'label' => "form.register.register"
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
