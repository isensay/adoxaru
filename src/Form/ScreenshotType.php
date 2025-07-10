<?php

// src/Form/ScreenshotType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Contracts\Translation\TranslatorInterface;
#use Symfony\Component\HttpFoundation\Request;

class ScreenshotType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', UrlType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => $this->translator->trans('base.not_blank_message', [], 'screenshots')]),
                    new Url([
                        'protocols' => ['http', 'https'],
                        'message'   => $this->translator->trans('base.url_message', [], 'screenshots')
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'https://example.com',
                    'class' => 'form-control form-control-lg',
                    'type' => 'url'
                ]
            ]);
    }
}