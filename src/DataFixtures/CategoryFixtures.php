<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

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
    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES as $k => $name) {
            $category = new Category();
            $category->setName($name);
            $category->setLink($this->name2link($name));
            $manager->persist($category);
            $this->addReference('category_' . $category->getLink(), $category);
        }
        $manager->flush();
    }
}