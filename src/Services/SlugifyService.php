<?php
// src/Service/SlugifyService.php
namespace App\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SlugifyService
{
    private $em;

    /**
     * FileManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function generateSlugify($name, $class)
    {
        $slug = $this->slugify($name);

        //Slugifier (mettre en minuscule tiret )*
        //Verfi bdd si nom existe -> -id
        $trick = $this->em->getRepository($class)->findOneBySlug($slug);
        if ($trick) {
            return $slug . '-' . count($this->em->getRepository($class)->findAll());
        }
        return $slug;
    }

    private function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
