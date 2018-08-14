<?php
// src/EventListener/ExceptionListener.php
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Psr\Log\LoggerInterface;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Security\User\Connect\ConnectException;

class ExceptionListener
{
    /** @var UrlGeneratorInterface **/
    protected $router;
    /** @var LoggerInterface **/
    protected $logger;
    /** @var SessionInterface **/
    protected $session;
    
    public function __construct(UrlGeneratorInterface $router, LoggerInterface $logger, SessionInterface $session)
    {
        $this->router = $router;
        $this->logger = $logger;
        $this->session = $session;
    }
    
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        switch (get_class($event->getException())) {
            case ConnectException::class: return $this->handleConnectException($event);
            case AccessDeniedException::class: return;
            default: return $this->handleException($event);
        }
    }
    
    protected function handleConnectException(GetResponseForExceptionEvent $event)
    {
        $event->stopPropagation();
        $event->setResponse(new RedirectResponse($this->router->generate('my_profile')));
        
        $this->session->getFlashbag()->add('error', $event->getException()->getMessage());
    }
    
    protected function handleException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $this->logger->error("{$exception->getFile()}.{$exception->getLine()}: {$exception->getMessage()}");
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