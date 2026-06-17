<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Form\ImagePickerType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

/**
 * @extends AbstractCrudController<Project>
 */
final class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            SlugField::new('slug')->setTargetFieldName('title'),
            TextareaField::new('summary')->hideOnIndex(),
            Field::new('coverImage', 'Cover image')
                ->setFormType(ImagePickerType::class)
                ->addFormTheme('form/image_picker.html.twig')
                ->setRequired(false)
                ->hideOnIndex(),
            ChoiceField::new('status'),
            ChoiceField::new('category'),
            UrlField::new('externalUrl')->setRequired(false)->hideOnIndex(),
            UrlField::new('repoUrl')->setRequired(false)->hideOnIndex(),
            BooleanField::new('isFeatured'),
            IntegerField::new('displayOrder'),
            AssociationField::new('tags'),
            DateTimeField::new('createdAt')->hideOnForm(),
            DateTimeField::new('updatedAt')->hideOnForm(),
        ];
    }
}
