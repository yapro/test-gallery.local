<?php

namespace AppBundle\Listener;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Bundle\PaginatorBundle\Twig\Extension\PaginationExtension;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Serializer\Serializer;

class ViewListener
{
	/**
	 * @var Serializer
	 */
	private $serializer;

	/**
	 * @var \Twig_Environment
	 */
	private $twig;

	/**
	 * @var PaginationExtension
	 */
	private $paginationExtension;

	/**
	 * ViewListener constructor.
	 * @param Serializer $serializer
	 * @param \Twig_Environment $twig
	 * @param PaginationExtension $paginationExtension
	 */
	public function __construct(
		Serializer $serializer,
		\Twig_Environment $twig,
		PaginationExtension $paginationExtension
	) {
		$this->serializer = $serializer;
		$this->twig = $twig;
		$this->paginationExtension = $paginationExtension;
	}

	/**
	 * @param GetResponseForControllerResultEvent $event
	 */
	public function onKernelResponse(GetResponseForControllerResultEvent $event)
	{
		$request = $event->getRequest();
		if (
			$request->isXmlHttpRequest() &&
			($result = $event->getControllerResult()) &&
			!empty($result['pagination']) &&
			$result['pagination'] instanceof SlidingPagination
		) {
			$result = array(
				'items' => $result['pagination']->getItems(),
				'pagination' => $result['pagination']->getPaginationData(),
				'paginationHtml' => $this->paginationExtension->render($this->twig, $result['pagination']),
			);
			$json = $this->serializer->serialize(
				$result,
				'json'
			);
			$response = new Response(
				$json,
				Response::HTTP_OK,
				[
					'Content-Type' => 'application/json',
				]
			);
			$event->setResponse($response);
		}
	}
}