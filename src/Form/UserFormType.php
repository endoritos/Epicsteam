<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('username', TextType::class, [
            'attr' => [
                'class' => 'bg-transparent block mt-10 mx-auto border-b-2 w-1/5 h-20 text-2xl outline-none',
                'placeholder' => 'Enter username...',
            ],
            'label' => false,
        ])
        ->add('Gender', ChoiceType::class, [
            'label' => false,
            'choices' => [
                'Male' => true,
                'Female' => false,
            ],
            'placeholder' => 'Choose gender', 
            'attr' => [
                'class' => 'bg-transparent block mt-10 mx-auto border-b-2 w-1/5 h-20 text-2xl outline-none',
            ],
        ])
        ->add('password', PasswordType::class, [
            'attr' => [
                'class' => 'bg-transparent block mt-10 mx-auto border-b-2 w-1/5 h-20 text-2xl outline-none',
                'placeholder' => 'Enter password...',
            ],
            'label' => false, 
        ])
        ->add('profilePictures', FileType::class, [
            'label' => 'Profile Picture (Image file)',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid image file',
                ])
            ],
            'attr' => [
                'class' => 'file:bg-transparent file:border-b-2 file:border-0 file:w-full file:h-20 file:text-6xl file:outline-none',
                'placeholder' => 'Upload profile picture...', // This placeholder won't directly apply to file inputs but kept for consistency
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
