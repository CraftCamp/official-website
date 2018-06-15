<?php
// src/EventListener/ExceptionListener.php
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use App\Security\User\Connect\ConnectException;

class ExceptionListener
{
    /** @var UrlGeneratorInterface **/
    protected $router;
    
    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }
    
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $event->stopPropagation();
        switch (get_class($event->getException())) {
            case ConnectException::class: return $this->handleConnectException($event);
            default: return $this->handleException($event);
        }
    }
    
    protected function handleConnectException(GetResponseForExceptionEvent $event)
    {
        $event->setResponse(new RedirectResponse($this->router->generate('my_profile')));
    }
    
    protected function handleException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $response = 
            ($event->getRequest()->getContentType() === 'json')
            ? new JsonResponse(['message' => $exception->getMessage()])
            : new Response($exception->getMessage())
        ;
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $event->setResponse($response);
    }
}