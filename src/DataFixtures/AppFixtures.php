<?php

namespace App\DataFixtures;

use App\Entity\MainPage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $mainPage = new MainPage();
        $mainPage->setMainText('Каркасыч всегда тебя будет выручать! Качество, гарантии, надежность, вареные яица и многое другое!');
        $mainPage->setAdvantage1("Плюс 1");
        $mainPage->setAdvantage2("Плюс 2");
        $mainPage->setAdvantage3("Плюс 3");
        $mainPage->setImages(['images/zabor2.png']);
        $manager->persist($mainPage);
        $manager->flush();
    }
}