<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add("price", PriceType::class, [
                "label" => false
            ])
            ->add("name", TextType::class, [
                "label" => "Designation",
                "empty_data" => ""
            ])
            ->add("description", TextareaType::class, [
                "label" => "Description",
                "empty_data" => ""
            ])
            ->add('image', ImageType::class, [
                "label" => false
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault("data_class", Product::class);
    }
}
