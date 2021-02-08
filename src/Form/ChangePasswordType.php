<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('old_password', PasswordType::class, [
                'label' => 'form.password.oldPassword',
                'required' => true,
                'mapped' => false,
            ])
            ->add('new_password', RepeatedType::class, [
                'required' => true,
                'mapped' => false,
                'invalid_message' => 'Le nouveau mot de passe et sa confirmation doivent être identique',
                'type' => PasswordType::class,
                'first_options' => ['label' => 'form.password.newPassword'],
                'second_options' => ['label' => 'form.password.confirmNewPassword']
                ])

            ->add('submit', SubmitType::class, [
                'label' => "form.password.updatePassword"
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
