<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserFixture extends Fixture
{
    private const USERS = [
        [
            'email' => 'nataliya_nedbaylo@yahoo.com',
            'password' => '12345',
            'role'  => ['ROLE_CONTRIBUTOR'],
            'programs' => ['The Big Bang Theory', 'Commissaire Megre', 'The End of The F**cking World']
        ],
        [
            'email' => 'tygryskuns@gmail.com',
            'password' => 'tygr',
            'role' => ['ROLE_ADMIN'],
            'programs' => ['Silence of lambs']
        ],
        [
            'email' => 'user1@gmail.com',
            'password' => 'tygr',
            'role' => ['ROLE_USER'],
            'programs' => ['Welcome to Symf', 'Zombie forever', 'Easy virtue', 'The sex and the city']
        ],
        [
            'email' => 'nataliya@yahoo.com',
            'password' => '12345',
            'role'  => ['ROLE_CONTRIBUTOR'],
        ],
    ];
    private UserPasswordHasherInterface $hasher;
    private SluggerInterface $slugger;
    
    public function __construct (UserPasswordHasherInterface $hasher, SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $val) {
            $user = new User();
            $user->setEmail($val['email']);
            $user->setRoles($val['role']);
            $user->setPassword($this->hasher->hashPassword($user, $val['password']));
            if (isset ($val['programs'])) {
                foreach ($val['programs'] as $program) {
                    $this->addReference('user_' . $this->slugger->slug($program), $user);
                }
            }
            $manager->persist($user);
        }
        $manager->flush();
    }
}
