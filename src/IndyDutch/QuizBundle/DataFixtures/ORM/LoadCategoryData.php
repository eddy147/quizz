<?php

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use IndyDutch\QuizBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $categoryGeography = new Category();
        $categoryGeography->setName('geography');
        $categoryScience = new Category();
        $categoryScience->setName('science');

        $manager->persist($categoryGeography);
        $manager->persist($categoryScience);
        $manager->flush();

        $this->addReference('category-geography', $categoryGeography);
        $this->addReference('category-science', $categoryScience);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
