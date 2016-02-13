<?php

namespace AppBundle\Listener;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Serializer\Serializer;

class ViewListener
{
	/**
	 * @var Serializer
	 */
	private $serializer;

	public function __construct(Serializer $serializer)
	{
		$this->serializer = $serializer;
	}

	public function onKernelResponse(GetResponseForControllerResultEvent $event)
	{
		$request = $event->getRequest();
		if (
			$request->isXmlHttpRequest() &&
			($result = $event->getControllerResult()) &&
			!empty($result['pagination']) &&
			$result['pagination'] instanceof SlidingPagination
		) {
			$json = $this->serializer->serialize($result['pagination'], 'json');
			$response = new Response($json);
			$response->headers->set('Content-Type', 'text/json');
			$event->setResponse($response);
		}
	}
}