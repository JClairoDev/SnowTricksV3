<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $grab=new Category();
        $grab->setName('grab');
        $manager->persist($grab);
        $this->addReference('grab',$grab);

        $indy=new Category();
        $indy->setName('indy');
        $manager->persist($indy);
        $this->addReference('indy',$indy);

        $manager->flush();
    }
}
