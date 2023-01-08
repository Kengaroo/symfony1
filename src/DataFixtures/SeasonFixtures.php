<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{    
    public const SEASONS =[
        [
            'number' => 1,
            'year' => 2008,            
            'program' => 'The Big Bang Theory'
        ],
        [
            'number' => 2,
            'year' => 2009,            
            'program' => 'The Big Bang Theory'
        ],
        [
            'number' => 1,
            'year' => 2001,            
            'program' => 'Commissaire Megre'
        ],
        [
            'number' => 2,
            'year' => 2022,            
            'program' => 'Commissaire Megre'
        ]
        ];

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        foreach (self::SEASONS as $info) {
            $season = new Season();
            $season->setNumber($info['number']);
            $season->setDescription($faker->paragraph());
            $season->setYear($info['year']);
            $season->setSlug($this->slugger->slug('season_' . $season->getNumber()));
            $season->setProgram($this->getReference('program_' . $this->slugger->slug($info['program'])));
            $this->addReference('season_' . $season->getNumber(). '_' . $this->slugger->slug($info['program']), $season);
            $manager->persist($season);
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
