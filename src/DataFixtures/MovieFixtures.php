<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $movie = new Movie();
        $movie->setTitle('The day Endy found love ');
        $movie->setReleaseYear(2023);
        $movie->setDescription('Resuguwed this girl at a bouth part met for dinks and fell with here story got marryed had kids happy everyafter');
        $movie->setImagePath('https://lillyathomeblog.files.wordpress.com/2020/11/2020-11-2220_56_27.574-0500.jpg?w=1024');

        $manager->persist($movie);

        $movie2 = new Movie();
        $movie2->setTitle('found love part 2 ');
        $movie2->setReleaseYear(2040);
        $movie2->setDescription('after good years in marrige is time to get a porche gtr');
        $movie2->setImagePath('https://lillyathomeblog.files.wordpress.com/2020/11/2020-11-2220_56_27.574-0500.jpg?w=1024');
        $manager->persist($movie2);

        $manager->flush();

    }
}