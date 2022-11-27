<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    
    public const PROGRAMS =[
        [
            'title' => 'The Big Bang Theory',
            'synopsis' => 'Des zombies envahissent la terre',
            'category' => 'Action',
            'actors' => [
                'George Clooney',
                'Jenny Lopes',
                'Meril Strip'
            ]
        ],
        [
            'title' => 'Silence of lambs',
            'synopsis' => 'Inimitable sir Antony Hopkins',
            'category' => 'Thriller',
            'actors' => [
                'Jonny Depp',
                'Jenny Lopes',
                'Vanessa Paradis'
            ]
        ],
        [
            'title' => 'Commissaire Megre',
            'synopsis' => 'Misterious murders, french Sherlock at work',
            'category' => 'Policier',
            'actors' => [
                'Gregory Peck',
                'Vivien Li',
                'Meril Strip'
            ]
        ],
        [
            'title' => 'The End of The F**cking World',
            'synopsis' => 'The End of the F***ing World is a British black comedy-drama television programme. The eight-part first series premiered its first episode on Channel 4 in the United Kingdom on 24 October 2017, after which the following episodes were released on All 4.',
            'poster' => '220px-The_End_of_the_F ing_World_logo-637a5dd1bf4d6.png',
            'category' => 'Action',
            'actors' => [
                'Chris Nolan',
                'James Bond',
                'Lara Fabian'
            ]
        ],
        [
            'title' => 'Welcome to Symf',
            'synopsis' => 'Step 1: use the genusName argument to query for a Genus object. But you guys already know how to do that: get the entity manager, get the Genus repository, and then call a method on it - like findOneBy()',
            'category' => 'Action',
            'actors' => [
                'Cristian Beil',
                'Jessica Bill',
                'Rachel Adams'
            ]
        ],
        [
            'title' => 'Zombie forever',
            'synopsis' => 'Des zombies envahissent la terre',
            'category' => 'Action',
            'actors' => [
                'Pippa Lee',
                'Julia Roberts',
                'Juilliet Binoch'
            ]
        ],
        [
            'title' => 'Easy virtue',
            'synopsis' => 'Old England, aristocracy, unconditional love',
            'poster' => '2021-12-18-Vika-Lviv-637a538fd7c06.jpg',
            'category' => 'Mélodrame',
            'actors' => [
                'Marla Singer',
                'Jenny Lopes',
                'Juilliet Binoch',
                'Vanessa Paradis'
            ]
        ]
    ];

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::PROGRAMS as $info) {
            $program = new Program();
            $program->setTitle($info['title']);
            $program->setSynopsis($info['synopsis']);
            $slug = $this->slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $program->setCategory($this->getReference('category_' . $this->slugger->slug($info['category'])));
            //$program->setLink(GlobalService::name2link($info['title']));
            if (isset($info['poster'])) {
                $program->setPoster($info['poster']);
            }
            foreach ($info['actors'] as $actorName) {
                $actor = new Actor();
                $actor->setName($actorName);
                $slug = $this->slugger->slug($actorName);
                $actor->setSlug($slug);
                $program->addActor($actor);
                $this->addReference('program_' . $program->getSlug(). '_' . $actor->getSlug(), $program);
            }
            $manager->persist($program);

            $this->addReference('program_' . $program->getSlug(), $program);
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
