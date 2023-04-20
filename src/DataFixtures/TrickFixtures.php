<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $user=new User();
        $user->setName('julien');
        $user->setPseudo('trickster');
        $user->setLastname('clairo');
        $user->setAvatar('photo1.jpg');
        $user->setEmail('trickster.julien@gmail.com');
        $user->setPassword('trickster');
        $manager->persist($user);

        $grab=$this->getReference('grab');

        $indy=new Trick();
        $indy->setName('indy');
        $indy->setCategoryId($grab);
        $indy->setUserId($user->getDefaultFixture());
        $indy->setDescription('description dela figure indygrab');
        $manager->persist($indy);


        $manager->flush();
    }
}
