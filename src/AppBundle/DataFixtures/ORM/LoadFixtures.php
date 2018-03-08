<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 3/8/2018
 * Time: 6:47 PM
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

class LoadFixtures implements FixtureInterface,ORMFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $loader = new NativeLoader();
        $objectSet = $loader->loadFile(__DIR__.'/fixtures.yml')->getObjects();
        foreach($objectSet as $object) {
            $manager->persist($object);
        }
        $manager->flush();

    }

}