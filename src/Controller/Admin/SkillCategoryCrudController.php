<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\SkillCategory;
use App\Form\ImagePickerType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SkillCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SkillCategory::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('subtitle')->setRequired(false),
            Field::new('icon')
                ->setFormType(ImagePickerType::class)
                ->addFormTheme('form/image_picker.html.twig')
                ->setRequired(false)
                ->hideOnIndex(),
            IntegerField::new('displayOrder'),
            AssociationField::new('skills')->hideOnForm(),
        ];
    }
}
