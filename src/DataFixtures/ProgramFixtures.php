<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use DateTime;

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
            ],
            'poster' => 'photo-5296311609821611965-y-63b2eb4bb5ff3230945045.jpg',
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
            'poster' => 'rond-63b2fac888b56405412817.png',
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
            'poster' => 'rondcolor-63b2e9ad5d203336850287.png',
            'category' => 'Mélodrame',
            'actors' => [
                'Marla Singer',
                'Jenny Lopes',
                'Juilliet Binoch',
                'Vanessa Paradis'
            ]
        ],
        [
            'title' => 'The sex and the city',
            'synopsis' => 'Sex and the City, le film, ou Sexe à New York au Québec, (Sex and the City), est un film américain réalisé par Michael Patrick King, sorti le 30 mai 2008 en Amérique du Nord, le 28 mai 2008 en France et le 4 juin 2008 en Belgique.
            Il s’agit de l’adaptation sur grand écran de la série télévisée Sex and the City.',
            'poster' => '18938386-jpg-c-310-420-x-f-jpg-q-x-xxyxx-63b2e0f006eaf714444360.jpg',
            'category' => 'Mélodrame',
            'actors' => [
                'Marla Singer',
                'Jenny Lopes',
                'Juilliet Binoch',
                'Vanessa Paradis'
            ]
        ],
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
            $program->setOwner($this->getReference('user_' . $program->getSlug()));
            foreach ($info['actors'] as $actorName) {
                $actor = new Actor();
                $actor->setName($actorName);
                $slug = $this->slugger->slug($actorName);
                $actor->setSlug($slug);
                $actor->setUpdatedAt(new DateTime("now"));
                $program->addActor($actor);
                $this->addReference('program_' . $program->getSlug(). '_' . $actor->getSlug(), $program);
            }
            $this->addReference('program_' . $program->getSlug(), $program);
            $program->setUpdatedAt(new DateTime("2022-11-23 00:00:00")); //var_dump($program->getUpdatedAt());
            $manager->persist($program);
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
