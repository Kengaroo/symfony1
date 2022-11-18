<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROGRAMS =[
        [
            'title' => 'The Big Bang Theory',
            'synopsis' => 'Des zombies envahissent la terre',
            'category' => 'Action'
        ],
        [
            'title' => 'Silence of lambs',
            'synopsis' => 'Inimitable sir Antony Hopkins',
            'category' => 'Thriller'
        ],
        [
            'title' => 'Comissaire Megre',
            'synopsis' => 'Misterious murders, french Sherlock at work',
            'category' => 'Policier'
        ],
        [
            'title' => 'Program 1',
            'synopsis' => 'Des zombies envahissent la terre',
            'category' => 'Action'
        ],
        [
            'title' => 'Program 2',
            'synopsis' => 'Des zombies envahissent la terre',
            'category' => 'Action'
        ],
        [
            'title' => 'Program 3',
            'synopsis' => 'Des zombies envahissent la terre',
            'category' => 'Action'
        ],
        [
            'title' => 'Easy virtue',
            'synopsis' => 'Old England, aristocracy, unconditional love',
            'category' => 'Mélodrame'
        ]
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::PROGRAMS as $info) {
            $program = new Program();
            $program->setTitle($info['title']);
            $program->setSynopsis($info['synopsis']);
            $program->setCategory($this->getReference('category_' . $info['category']));
            
            $manager->persist($program);
            $this->addReference('program_' . $info['title'], $program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          CategoryFixtures::class,
        ];

    }
}
