<?php
namespace MaillingListBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionListener
{
	protected $twig;
	
	public function __construct(\Twig_Environment $twig)
	{
		$this->twig = $twig;
	}
	
	public function onKernelException(GetResponseForExceptionEvent $event)
	{
		// Récupération de l'exception
		$exception = $event->getException();
		
		$intercept = true;
		switch(true)
		{
			case $exception instanceof \InvalidArgumentException:
				// Récupération du message
				$message = $exception->getMessage();
				break;
				
			case $exception instanceof \GuzzleHttp\Exception\ClientException:
				// Exception dans la requête vers OVH
				$messageReturn = json_decode($exception->getResponse()->getBody()->getContents());

				$message = $messageReturn->message;
				if( $messageReturn->httpCode[0] == 4) {
					$exception = new HttpException($messageReturn->httpCode, $message);
				}
				break;
				
			default:
				$intercept = false;
				break;
		}

		// Si l'on intercepte l'exception
		if($intercept) {
			// Rendu du message d'erreur
			$response = new Response();
			
			// HttpExceptionInterface est un type spécial dont on garde le code
			if ($exception instanceof HttpExceptionInterface) {
				$response->setStatusCode($exception->getStatusCode());
				$response->headers->replace($exception->getHeaders());
			} else {
				$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			}
			
			$render = $this->twig->render('::Exception\exception.html.twig', array(
					'message' => $message,
					'code' => $response->getStatusCode()
			));
			
			$response->setContent($render);
			
			// Envoi de la réponse
			$event->setResponse($response);
		}
	}
}
