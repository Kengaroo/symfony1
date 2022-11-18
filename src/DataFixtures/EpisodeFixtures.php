<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const EPISODES =[
        [
            'title' => "Pilot",
            'synopsis' => 'Des zombies envahissent la terre',
            'number' => 1,
            'season' => 1
        ],
        [
            'title' => "The Big Bran Hypothesis",
            'synopsis' => 'Inimitable sir Antony Hopkins',
            'number' => 2,
            'season' => 1
        ],
        [
            'title' => "The Fuzzy Boots Corollary",
            'synopsis' => 'Misterious murders, french Sherlock at work',
            'number' => 3,
            'season' => 1
        ],
        [
            'title' => "The Bad Fish Paradigm",
            'synopsis' => 'Des zombies envahissent la terre',
            'number' => 1,
            'season' => 2
        ]
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::EPISODES as $info) {
            $episode = new Episode();
            $episode->setTitle($info['title']);
            $episode->setNumber($info['number']);
            $episode->setSynopsis($info['synopsis']);
            $episode->setSeason($this->getReference('season_' . $info['season']));

            $manager->persist($episode);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont EpisodeFixtures dépend
        return [
          SeasonFixtures::class,
        ];

    }
}
