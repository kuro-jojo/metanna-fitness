<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {   
        $faker = Factory::create();

        $category1 = new Category();
        $category1->setName('Boissons');

        $category2 = new Category();
        $category2->setName('Equipement sportifs');

        $category3 = new Category();
        $category3->setName('Vêtements');
        
        for ($i=0; $i < 12; $i++) { 
            $article = new Article;
            $article->setLabel($faker->word);
            $article->setStock(rand(0,20));
            $article->setPrice($faker->numberBetween(50,5000));
            $article->setCategory($faker->randomElement($array = array($category1,$category2,$category3)));
            $manager->persist($article);
        }
        $manager->persist($category1);
        $manager->persist($category2);
        $manager->persist($category3);
        
        $manager->flush();
    }
}
