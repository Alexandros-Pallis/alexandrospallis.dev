<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Credential;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CredentialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Credential::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextField::new('issuer'),
            IntegerField::new('year'),
            ChoiceField::new('type'),
            TextField::new('icon')->setRequired(false),
            IntegerField::new('displayOrder'),
        ];
    }
}
