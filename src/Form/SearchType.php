<?php

namespace App\Form;

use App\Entity\Search;
use Doctrine\DBAL\Types\IntegerType;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maxPrice', IntegerType::class , ['required' =>false , 'label'=>false,
        'placeholder' => "prix minimal"])
            ->add('minPrice', IntegerType::class , ['required' =>false , 'label'=>false,
    'placeholder' => "prix maximal"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'methode' =>'get' , 'csrf_protection ' =>false
        ]);
    }
}
