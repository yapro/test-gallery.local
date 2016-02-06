<?php

namespace AppBundle\Controller\Action;

use AppBundle\Entity\Image;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AlbumAction
{
	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * @param ContainerInterface $serviceContainer
	 */
	public function __construct(ContainerInterface $serviceContainer)
	{
		$this->container = $serviceContainer;
	}

	/**
	 * @param int $id
	 * @param int $page
	 * @return array
	 */
	public function getResult($id, $page)
	{
		$qb = $this->container->get('doctrine.orm.entity_manager')->createQueryBuilder()
			->select('Image')
			->from(Image::class, 'Image');
		if (!empty($id)) {
			$qb->where('Image.albumId = :albumId')
				->setParameter('albumId', $id);
		}
		/** @var SlidingPagination $pagination */
		$pagination = $this->container->get('knp_paginator')->paginate(
			$qb->getQuery(),
			($page === 0 ? 1 : $page),
			10,
			array('pageParameterName' => 'page')
		);
		if(count($pagination->getItems()) === 0){
			throw new NotFoundHttpException('Images not found');
		}
		$pagination->setUsedRoute('DefaultAlbumPage');
		return array('pagination' => $pagination);
	}
}