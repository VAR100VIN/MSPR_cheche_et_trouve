<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Doctrine\ORM\EntityManagerInterface;

use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email'),
            TextField::new('plainPassword', 'Mot de passe')->setFormType(PasswordType::class),
            ArrayField::new('roles'),
            BooleanField::new('isVerified', 'est vérifié'),
        ];
    }
    
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('isVerified', 'est vérifié'))
            ;
    }
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
{
    $this->updatePassword($entityManager->getRepository(\App\Entity\User::class), $entityInstance);
    parent::updateEntity($entityManager, $entityInstance);
}

public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
{
    $this->updatePassword($entityInstance);
    parent::persistEntity($entityManager, $entityInstance);
}

private function updatePassword(UserRepository $userRepository, User $user): void
{
    if ($user->getPlainPassword() == '') return;
    $userRepository->setNewPassword($user, $user->getPlainPassword());
}
}
