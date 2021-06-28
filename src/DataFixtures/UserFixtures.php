<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User(
            1,
            'sandovalcarlosaugusto@gmail.com'
        );
        $password = $this->userPasswordHasher->hashPassword(
            $user,
            'IsabellaSantiago'
        );
        $user->setPassword($password);
        $manager->persist($user);
        $manager->flush();
    }
}
