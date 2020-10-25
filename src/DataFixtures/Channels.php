<?php

namespace App\DataFixtures;

use App\Entity\Channel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class Channels extends Fixture
{
    public const CHANNEL_REFERENCE = 'channel-reference';

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('pt_PT');

        for ($i = 0; $i < 11; $i++) {
            $channel = new Channel();
            $channel->setName($faker->randomElement($array =
                array('website', 'mobile', 'internal', 'external',
                    'api', 'soccer', 'tennis', 'car', 'bicycle', 'motorcycle')));
            $this->addReference(self::CHANNEL_REFERENCE . $i, $channel);
            $manager->persist($channel);
        }
        $manager->flush();
    }
}
