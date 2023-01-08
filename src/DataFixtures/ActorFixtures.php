<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Actor;
use Symfony\Component\String\Slugger\SluggerInterface;
use DateTime;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public const ACTORS = [
        ['name' => 'Pippa Lee', 'programs' => ['Zombie forever']],
        ['name' => 'Julia Roberts', 'programs' => ['Zombie forever']],
        ['name' => 'Juilliet Binoch', 'programs' => ['Zombie forever']],
        ['name' => 'Cristian Beil', 'programs' => ['Welcome to Symf']],
        ['name' => 'Jessica Bill', 'programs' => ['Welcome to Symf']],
        ['name' => 'Rachel Adams', 'programs' => ['Welcome to Symf']],
        ['name' => 'Gregory Peck', 'programs' => ['Commissaire Megre']],
        ['name' => 'Vivien Li', 'programs' => ['Commissaire Megre']],
        ['name' => 'Meril Strip', 'programs' => ['Commissaire Megre', 'The Big Bang Theory'], 'photo' => 'ms-63b33d7f63994074560753.jpg'],
        ['name' => 'Jonny Depp', 'programs' => ['Silence of lambs',]],
        ['name' => 'Jenny Lopes', 'programs' => ['Silence of lambs', 'The Big Bang Theory', 'Easy virtue']],
        ['name' => 'Vanessa Paradis', 'programs' => ['Silence of lambs', 'Easy virtue']],
        ['name' => 'George Clooney', 'programs' => ['The Big Bang Theory'], 'photo' => 'jk-63b330341b4d3034150033.jpg'],
        ['name' => 'Marla Singer', 'programs' => ['Easy virtue']],
        ['name' => 'Chris Nolan', 'programs' => ['The End of The F**cking World']],
        ['name' => 'James Bond', 'programs' => ['The End of The F**cking World']],
        ['name' => 'Lara Fabian', 'programs' => ['The End of The F**cking World']]
    ];


    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::ACTORS as $info) {
            $actor = new Actor();
            $actor->setName($info['name']);
            $actor->setSlug($this->slugger->slug($info['name']));
            foreach ($info['programs'] as $program) {
                $actor->addProgram($this->getReference('program_' . $this->slugger->slug($program) . '_' . $actor->getSlug()));  
            }
            if (isset($info['photo'])) {
                $actor->setPhoto($info['photo']);
            }
            $actor->setUpdatedAt(new \DateTime('now'));
            $manager->persist($actor);
            $this->addReference('actor_' . $this->slugger->slug($actor->getName()), $actor);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          ProgramFixtures::class,
        ];
    }
}
