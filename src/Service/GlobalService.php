<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class GlobalService
{
    protected $requestStack;

    static public function name2link($name)
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

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getRequest()
    {
        $request = $this->requestStack->getCurrentRequest();
        dump($request); die;
    }
}
