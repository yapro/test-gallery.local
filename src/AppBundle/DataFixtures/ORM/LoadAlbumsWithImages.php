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
		for ($i = 0; $i < 5; $i++) {
			$album = new Album();
			$album->setName('Album ' . $i);
			$manager->persist($album);
			$max = $i === 0 ? 5 : 21;
			for ($n = 0; $n < $max; $n++) {
				$image = new Image();
				$image->setAlbum($album);
				$manager->persist($image);
			}
		}
		$manager->flush();
	}
}