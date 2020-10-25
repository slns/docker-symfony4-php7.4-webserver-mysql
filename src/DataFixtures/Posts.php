<?php

namespace App\DataFixtures;

use App\DataFixtures\Channels;
use App\Entity\Posts as Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;


class Posts extends Fixture
{
    public function load(ObjectManager $manager)
    {
       // $em = env('EntityManager')->getFacadeRoot();
        $faker = Faker\Factory::create('pt_PT');

        for ($i = 0; $i < 100; $i++) {
            $post = new Post();
            $post->setTitle($faker->word);
            $post->setDescription($faker->sentence($nbWords = 6, $variableNbWords = true));
            $post->setContent($faker->text($maxNbChars = 200));
            $post->setStatus($faker->boolean);
            $post->setCreatedAt($faker->dateTime());
            $post->setUpdatedAt($faker->dateTime());
            $post->setChannel($this->getReference(Channels::CHANNEL_REFERENCE . $faker->numberBetween($min = 1, $max = 10)));
            $manager->persist($post);
        }

        $manager->flush();
    }
}
