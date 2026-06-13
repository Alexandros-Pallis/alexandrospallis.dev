<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Image;
use App\Repository\ImageRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImagePickerType extends AbstractType
{
    public function __construct(
        private readonly ImageRepository $imageRepository,
        private readonly UploaderHelper $uploaderHelper,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new CallbackTransformer(
            fn (?Image $image): string => $image?->getId() !== null ? (string) $image->getId() : '',
            fn (?string $id): ?Image => ($id ?? '') === '' ? null : $this->imageRepository->find($id),
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $image = $form->getData();
        $view->vars['image'] = $image instanceof Image ? $image : null;
        $view->vars['image_url'] = $image instanceof Image && null !== $image->getImageName()
            ? $this->uploaderHelper->asset($image, 'imageFile')
            : null;
    }

    public function getParent(): string
    {
        return HiddenType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // EasyAdmin treats ManyToOne properties as associations and passes EntityType-style
        // options ("class", "query_builder") regardless of the configured form type.
        $resolver->setDefined(['class', 'query_builder']);
    }
}
