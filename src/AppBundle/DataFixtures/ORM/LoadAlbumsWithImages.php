<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Album;
use AppBundle\Entity\Image;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadAlbumsWithImages implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		for ($i = 0; $i < 11; $i++) {
			$album = new Album();
			$album->setName('Album ' . $i);
			$manager->persist($album);
			for ($n = 0; $n < 11; $n++) {
				$image = new Image();
				$image->setAlbum($album);
				$manager->persist($image);
			}
		}
		$manager->flush();
	}
}