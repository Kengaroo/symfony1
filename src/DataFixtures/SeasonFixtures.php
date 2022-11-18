<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public const SEASONS =[
        [
            'number' => 1,
            'year' => 2008,
            'description' => 'Des zombies envahissent la terre',
            'program' => 'The Big Bang Theory'
        ],
        [
            'number' => 2,
            'year' => 2009,
            'description' => 'Des zombies envahissent la terre',
            'program' => 'The Big Bang Theory'
        ]
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::SEASONS as $info) {
            $season = new Season();
            $season->setNumber($info['number']);
            $season->setDescription($info['description']);
            $season->setYear($info['year']);
            $season->setProgram($this->getReference('program_' . $info['program']));

            $manager->persist($season);
            $this->addReference('season_' . $info['number'], $season);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont SeasonFixtures dépend
        return [
          ProgramFixtures::class,
        ];

    }
}
