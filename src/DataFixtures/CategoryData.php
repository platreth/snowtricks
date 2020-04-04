<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CategoryData extends Fixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $category = new Category;
        $category->setName("grabs");
        $manager->persist($category);
        $manager->flush();

        $category = new Category;
        $category->setName("rotations");
        $manager->persist($category);
        $manager->flush();

        $category = new Category;
        $category->setName("flips");
        $manager->persist($category);
        $manager->flush();

        $category = new Category;
        $category->setName("slides");
        $manager->persist($category);
        $manager->flush();
    }
}
