<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\State;
use Doctrine\ORM\Query\Expr\Select;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductCardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product_code', Input::class, [
                'mapped' => false,
                new Assert\NotBlank([
                    'message' => 'Please enter a product_code',
                ]),
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('count', Input::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('state', ChoiceType::class, [
                'choices'  => [
                    'disable' => State::STATE_DISABLE,
                    'enable' => State::STATE_ENABLE,
                    'hidden' => State::STATE_HIDDEN
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
