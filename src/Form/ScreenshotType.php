<?php

// src/Form/ScreenshotType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ScreenshotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', UrlType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Пожалуйста, введите URL']),
                    new Url([
                        'protocols' => ['http', 'https'],
                        'message' => 'Введите корректный URL'
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'https://example.com',
                    'class' => 'form-control form-control-lg'
                ]
            ]);
    }
}