<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class JsonRequestListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if ($request->getContentType() !== 'json') {
            return;
        }
        foreach(json_decode($request->getContent(), true) as $key => $value) {
            $request->request->set($key, $value);
        }
    }
}