<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UsersFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {
         $user = new User();
         $user->setEmail('admin@admin.com');
        $user->setPassword($this->userPasswordHasher->hashPassword($user, 'password'));
         $user->setRoles(['ROLE_ADMIN']);
         $user->setUsername('root');

         $manager->persist($user);

        $manager->flush();
    }
}
