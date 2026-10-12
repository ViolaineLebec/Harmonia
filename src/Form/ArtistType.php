<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stageName', null, [
                'label' => 'Nom de scène',
                'attr' => [
                    'class' => 'm-3'
                ]
            ])
            ->add('biography', null, [
                'label' => 'Biographie',
                'attr' => [
                    'class' => 'm-3',
                    'rows' => 5
                ]
            ])
            ->add('country', null, [
                'label' => 'Pays',
                'attr' => [
                    'class' => 'm-3'
                ]
            ])
            ->add('Submit', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => [
                    'class' => 'm-3 btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
