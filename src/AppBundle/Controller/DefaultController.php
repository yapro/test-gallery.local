<?php

namespace AppBundle\Controller;

use AppBundle\Controller\Action\AlbumAction;
use AppBundle\Entity\Album;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
	/**
	 * @return array
	 *
	 * @Route("/", name="DefaultAlbumList")
	 * @Template()
	 */
	public function indexAction()
	{
		return [
			'albums' => $this->get('doctrine')->getRepository(Album::class)->findBy([], ['id' => 'ASC'])
		];
	}

	/**
	 * @param int $id
	 * @param int $page
	 * @return array
	 *
	 * @Route("/album/{id}", defaults={"id" = 0}, name="DefaultAlbum", requirements={
	 *     "id": "\d+"
	 * })
	 * @Route("/album/{id}/page/{page}", defaults={"id" = 0, "page" = 1}, name="DefaultAlbumPage", requirements={
	 *     "id": "\d+",
	 *     "page": "\d+"
	 * })
	 * @Template()
	 */
	public function albumAction($id = 0, $page = 1)
	{
		return (new AlbumAction($this->container))->getResult($id, $page);
	}
}
