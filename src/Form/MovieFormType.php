<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\Game; // Adjusted to use Game entity
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; 

class MovieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('gameName', TextType::class, [
            'attr' => [
                'class' => 'bg-transparent block border-b-2 w-full h-20 text-6xl outline-none',
                'placeholder' => 'Enter game name...',
            ],
            'label' => false,
            'required' => false
        ])
        ->add('link', UrlType::class, [
            'attr' => [
                'class' => 'bg-transparent block mt-10 border-b-2 w-full h-20 text-6xl outline-none',
                'placeholder' => 'Enter game link...'
            ],
            'label' => false,
            'required' => false
        ])
        ->add('isPublic', ChoiceType::class, [
            'choices' => [
                'Private' => true,
                'Public' => false,
            ],
            'placeholder' => 'Select Visibility',
            'attr' => [
                'class' => 'bg-transparent block mt-10 mx-auto border-b-2 w-1/5 h-20 text-2xl outline-none',
            ],
            'label' => 'Game Visibility', 
        ])
        ->add('imagePath', FileType::class, [
            'required' => false,
            'mapped' => false,
            'attr' => [
                'class' => 'file:bg-transparent file:border-b-2 file:border-0 file:w-full file:h-20 file:text-6xl file:outline-none',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class, // Adjusted to Game class
        ]);
    }
}
