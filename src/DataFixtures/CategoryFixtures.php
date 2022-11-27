<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{
    public const CATEGORIES = [
        'Action',
        'Adventure',
        'Animation',
        'Fantstique',
        'Policier',
        'Horreur',
        'Thriller',
        'Mélodrame',
        'Comédie'
    ];

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES as $k => $name) {
            $category = new Category();
            $category->setName($name);       
            $category->setSlug($this->slugger->slug($name));  
            $manager->persist($category);
            $this->addReference('category_' . $category->getSlug(), $category);
        }
        $manager->flush();
    }
}