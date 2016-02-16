<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Album;
use AppBundle\Entity\Image;
use Doctrine\ORM\EntityManager;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AlbumManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * AlbumManager constructor.
     * @param EntityManager $em
     * @param Paginator $paginator
     */
    public function __construct(EntityManager $em, Paginator $paginator)
    {
        $this->em = $em;
        $this->paginator = $paginator;
    }

    /**
     * @param int $id
     * @param int $page
     * @return array
     */
    public function getResult($id, $page)
    {
        if ($id === 0) {
            throw new NotFoundHttpException('Album does not exist');
        }
        $album = $this->em->getRepository(Album::class)->find($id);
        if (!$album instanceof Album) {
            throw new NotFoundHttpException('Album not found');
        }
        $query = $this->em->getRepository(Image::class)->getPaginationQuery($album);
        /** @var SlidingPagination $pagination */
        $pagination = $this->paginator->paginate(
            $query,
            ($page < 1 ? 1 : $page),
            10,
            array('pageParameterName' => 'page')
        );
        if (count($pagination->getItems()) === 0) {
            throw new NotFoundHttpException('Images not found');
        }
        $pagination->setUsedRoute('DefaultAlbumPage');
        return array('pagination' => $pagination);
    }
}