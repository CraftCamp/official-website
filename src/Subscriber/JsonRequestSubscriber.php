<?php

namespace App\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;

use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\HttpFoundation\JsonResponse;


use Symfony\Component\HttpKernel\Exception\HttpException;

class JsonRequestSubscriber implements EventSubscriberInterface
{
    /** @var TranslatorInterface **/
    protected $translator;
    
    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'onKernelRequest',
            'kernel.exception' => 'onKernelException'
        ];
    }
    
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!$this->isJsonRequest($event) || empty($request->getContent())) {
            return;
        }
        $request->request->add(json_decode($request->getContent(), true));
    }
    
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$this->isJsonRequest($event)) {
            return;
        }
        $exception = $event->getException();
        $event->setResponse(new JsonResponse([
            'message' => $this->translator->trans($exception->getMessage())
        ], ($exception instanceof HttpException) ? $exception->getStatusCode(): 500));
        $event->stopPropagation();
    }
    
    protected function isJsonRequest(KernelEvent $event): bool
    {
        return ($event->getRequest()->getContentType() === 'json');
    }
}
