<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ImageRepository extends EntityRepository
{
	/**
	 * @param int $id
	 * @return Query
	 */
	public function getPaginationQuery($id)
	{
		$qb = $this->createQueryBuilder('Image')
			->select('Image');
		if (!empty($id)) {
			$qb->where('Image.albumId = :albumId')
				->setParameter('albumId', $id);
		}
		return $qb->getQuery();
	}
}
