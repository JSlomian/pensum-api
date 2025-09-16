<?php

namespace App\EventSubscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;

class JWTLoginSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_authentication_success' => 'onAuthenticationSuccess',
        ];
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $response = $event->getResponse();

        $response->headers->setCookie(
            Cookie::create('auth.token', $data['token'])
                ->withDomain('.jslomian.dev')
                ->withSecure(true)
                ->withHttpOnly(false)
                ->withSameSite(Cookie::SAMESITE_NONE
                )
        );
        $response->headers->setCookie(
            Cookie::create('auth.refresh', $data['refresh_token'])
                ->withDomain('.jslomian.dev')
                ->withSecure(true)
                ->withHttpOnly(true)
                ->withSameSite(Cookie::SAMESITE_NONE)
        );
    }
}
