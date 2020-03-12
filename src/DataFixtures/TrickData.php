<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TrickData extends Fixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $trick = new Trick();
            $user = $manager->getRepository(User::class)->findOneBy(array('email' => "hugo.platret@gmail.com"));
            $category = $manager->getRepository(Category::class)->findOneBy(array("name" => "grabs"));
            $trick->setCategory($category);
            $trick->setSlug('test' . $i);
            $trick->setName('Trick de test' . $i);
            $trick->setDateCreate(new \DateTime('now'));
            $trick->setDescription('Ceci est une description' . $i);
            $manager->persist($trick);
        }
        $manager->flush();
    }
}