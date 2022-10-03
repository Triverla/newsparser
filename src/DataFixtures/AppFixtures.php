<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasherInterface;

    public function __construct (UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager): void
    {
        $roles = ['ROLE_ADMIN', 'ROLE_MODERATOR'];
        $names = ['John Doe', 'Jane Doe'];
        $emails = ['admin@newsparser.com', 'moderator@newsparser.com'];
        for ($i = 0; $i < 2; $i++) {
            $user = new User();
            $user->setName($names[$i]);
            $user->setEmail($emails[$i]);
            $user->setPassword(
                $this->userPasswordHasherInterface->hashPassword(
                    $user, 'password'
                )
            );
            $user->setRoles((array)$roles[$i]);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
