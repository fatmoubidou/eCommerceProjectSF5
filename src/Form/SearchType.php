<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Search;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('string', TextType::class,[
            'label' => false,
            'required' => false,
            'attr' => [
                    'placeholder' => 'form.search.yourSearch'
                ]
            ])
            ->add('categories', EntityType::class, [
                'label' => 'form.search.byCategory',
                'required' => false,
                'class' => Category::class,
                'multiple' => true,
                'expanded' => true,
                ])

            ->add('submit', SubmitType::class, [
                'label' => "form.search.filter",
                'attr' => [
                        'class' => 'btn-block btn-primary'
                    ]
                ])
        ;
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'crsf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
