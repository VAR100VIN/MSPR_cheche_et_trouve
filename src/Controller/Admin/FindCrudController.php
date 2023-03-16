<?php

namespace App\Controller\Admin;

use App\Entity\Find;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
class FindCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Find::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            AssociationField::new('user'),
            AssociationField::new('plant'),
            TextField::new('title'),
            TextField::new('longitude'),
            TextField::new('latitude'),
            TextEditorField::new('description'),
            ImageField::new('url')
                ->setUploadDir('public/medias/uploads')
                ->setBasePath('medias/uploads'),
        ];
    }

    
    
}
