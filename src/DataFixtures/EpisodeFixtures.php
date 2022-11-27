<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const EPISODES =[
        [
            'title' => "Pilot",
            'synopsis' => 'Des zombies envahissent la terre',
            'number' => 1,
            'season' => 1,
            'program' => 'The Big Bang Theory',
            'duration' => 45
        ],
        [
            'title' => "The Big Bran Hypothesis",
            'synopsis' => 'Inimitable sir Antony Hopkins',
            'number' => 2,
            'season' => 1,
            'program' => 'The Big Bang Theory',
            'duration' => 55
        ],
        [
            'title' => "The Fuzzy Boots Corollary",
            'synopsis' => 'Misterious murders, french Sherlock at work',
            'number' => 3,
            'season' => 1,
            'program' => 'Commissaire Megre',
            'duration' => 68
        ],
        [
            'title' => "The Bad Fish Paradigm",
            'synopsis' => 'Des zombies envahissent la terre',
            'number' => 1,
            'season' => 2,
            'program' => 'Commissaire Megre',
            'duration' => 113
        ]
    ];

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        foreach (self::EPISODES as $info) {
            $episode = new Episode();
            $episode->setTitle($info['title']);
            $slug = $this->slugger->slug($episode->getTitle());
            $episode->setSlug($slug);
            $episode->setNumber($info['number']);
            $episode->setDuration($info['duration']);
            //$episode->setSynopsis($info['synopsis']);
            $episode->setSynopsis($faker->paragraph());   
            $episode->setSeason($this->getReference('season_' . $info['season']. '_' . $this->slugger->slug($info['program'])));

            $manager->persist($episode);
        }
                
        $manager->flush();
    }

    public function getDependencies()
    {        
        return [
          SeasonFixtures::class,
        ];

    }
}
