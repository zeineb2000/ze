<?php

namespace App\Form;

use App\Entity\Discount;
use App\Entity\Formation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\TextaType;


class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('imageFile',VichImageType::class)
            ->add('prix')
            ->add('place')
            ->add( 'iddiscount', EntityType::class, [
                'class' => Discount::class,
                'choice_label' => 'percentege',])
            ->add('location')
            ->add('lat', HiddenType::class)
            ->add('lng', HiddenType::class)
            ->add('La_g', HiddenType::class)
            ->add('La_i', HiddenType::class)
            ->add('Ra_g', HiddenType::class)
            ->add('Ra_i', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
            'required'=>false,
        ]);
    }
}
