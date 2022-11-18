<?php
use Symfony\Config\TwigConfig;
use App\Repository\CategoryRepository;
 static function (TwigConfig $twig, CategoryRepository $categoryRepository) {
    $categories = $categoryRepository->findAll();
    
    $twig = new Twig_Environment($loader);
    $twig->addGlobal('myvar', $myGlobalVar);

    return $twig->global('categoriesAll')->value($categories);
};