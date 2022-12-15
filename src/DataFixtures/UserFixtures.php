<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $options = [
            'cost' => 4,
            'time_cost'=> 3, # Lowest possible value for argon
            'memory_cost'=> 10 # Lowest possible value for argon
        ];
        $encrypted = password_hash("defaultuser", PASSWORD_BCRYPT, $options);
        $user = new User();
        $user->setEmail('admin@gmail.com');
        $user->setPassword($encrypted);
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setAvatar('assets\medias\user.png');
        $user->setExp(0);
        $user->setIsVerified(1);
        $manager->persist($user);
        $manager->flush();   
    }
}
