<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserAdminFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Reserved for future admin-specific fixtures.
    }
}
