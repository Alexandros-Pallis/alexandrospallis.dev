<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\TimelineEntry;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @extends AbstractCrudController<TimelineEntry>
 */
final class TimelineEntryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TimelineEntry::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('phaseLabel'),
            TextField::new('title'),
            TextareaField::new('description')->hideOnIndex(),
            BooleanField::new('isCurrent'),
            IntegerField::new('displayOrder'),
            AssociationField::new('tags'),
        ];
    }
}
