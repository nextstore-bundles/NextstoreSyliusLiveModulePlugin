<?php

declare(strict_types=1);

namespace Nextstore\SyliusLiveModulePlugin\Form\Type;

use Nextstore\SyliusLiveModulePlugin\Model\Live;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class LiveType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'sylius.ui.code',
                'constraints' => [
                    new NotBlank(null, 'Code cannot be empty')
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'sylius.ui.name',
                'constraints' => [
                    new NotBlank(null, 'Name cannot be empty')
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'sylius.ui.description',
            ])
            ->add('fbLiveUrl', UrlType::class, [
                'label' => 'nextstore_sylius_live_module.ui.fb_live_url',
            ])
            ->add('fbLiveEmbedCode', TextType::class, [
                'label' => 'nextstore_sylius_live_module.ui.fb_live_embed_code',
            ])
            ->add('shop', ChoiceType::class, [
                'label' => 'nextstore_sylius_live_module.ui.shop',
                'choices' => [
                    'Emart' => Live::SHOP_EMART,
                    'Costco' => Live::SHOP_COSTCO
                ]
            ])
            ->add('startDate', DateTimeType::class, [
                'date_widget'   => 'single_text',
                'time_widget'   => 'single_text',
                'date_label' => 'Starts On',
                'label' => 'nextstore_sylius_live_module.ui.start_date',
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius.ui.enabled'
            ])
            ->add('thumbnailFile', FileType::class, [
                'label' => 'sylius.ui.image'
            ])
        ;
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix(): string
    {
        return 'nextstore_sylius_live_module_live';
    }
}
