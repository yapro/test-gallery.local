<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Album;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ImageRepository extends EntityRepository
{
    /**
     * @param Album $album
     * @return Query
     */
    public function getPaginationQuery(Album $album)
    {
        return $this->createQueryBuilder('Image')
            ->select('Image')
            ->where('Image.albumId = :albumId')
            ->setParameter('albumId', $album->getId())
            ->getQuery();
    }
}
