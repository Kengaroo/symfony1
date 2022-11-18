<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    function name2link($name)
    {
        $link = mb_strtolower($name, 'utf-8');
        //Pre-processing for French
            $patterns     = ['à', 'â', 'ç', 'è', 'é', 'ê', 'ë', 'î', 'ï', 'ô', 'ù', 'û', 'ü', 'ÿ'];
            $replacements = ['a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'o', 'u', 'u', 'u', 'y'];
            $link         = str_replace($patterns, $replacements, $link);
    
        $patterns     = ["/[^a-z0-9A-Z\s-]/", "/[\s\-\s]/", "/[\s]+/", "/[\s]+/"];
        $replacements = ['', ' ', '-', '-'];
        $link         = trim($link);
        $link         = preg_replace($patterns, $replacements, $link);
        $link         = trim($link, ' -');
    
        return $link;
    }
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
        public const PROGRAMS = [
            'The Big Bang Theory', 'Silence of lambs', 'Comissaire Megre', 'Program 1', 'Program 2', 'Program 3', 'Easy virtue'        
        ];
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
/*
        for ($i = 0; $i < 50 ; $i++) {
            $season = new Season();
            $season->setNumber($i%5+1);//$faker->numberBetween(1, 5));
            $season->setDescription($faker->paragraph());
            $season->setYear($faker->year());
            $season->setProgram($this->getReference('program_' . $this->name2link(self::PROGRAMS[rand(0,6)])));

            $manager->persist($season);
            $add = 'season_' . $season->getNumber();
            if (null === $this->getReference($add)) {
                $this->addReference('season_' . $season->getNumber(), $season);
            }
        }
        $manager->flush();
*/
        foreach (self::SEASONS as $info) {
            $season = new Season();
            $season->setNumber($info['number']);
            $season->setDescription($faker->paragraph());
            $season->setYear($faker->year());
            $season->setProgram($this->getReference('program_' . $this->name2link(self::PROGRAMS[rand(0,6)])));
            $this->addReference('season_' . $season->getNumber(), $season);
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
