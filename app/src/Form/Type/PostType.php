<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class PostType
 * @package App\Form\Type
 */
class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('content', TextType::class)
            ->add('file', FileType::class, [
                'label' => 'Post image',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    self::getFileValidator()
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Create post']);
    }

    public static function getFileValidator(): File
    {
        return new File([
            'maxSize' => '1024k',
            'mimeTypes' => [
                'image/jpeg',
                'image/jpg',
            ],
            'mimeTypesMessage' => 'Please upload a valid JPG image',
        ]);
    }
}