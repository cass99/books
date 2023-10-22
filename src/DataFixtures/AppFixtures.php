<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 3; $i++) {
            $author = new Author();
            $author->setFullName('Piotr Mierzejewski ' . $i);
            $author->setCountry('Poland');
            $manager->persist($author);

            $book = new Book();
            $book->setName('W pustyni i w puszczy ' . $i);
            $book->setPublishingHouse('Znak');
            $book->setPages($i);
            $book->setAuthor($author);
            $manager->persist($book);
        }

        $manager->flush();
    }
}
