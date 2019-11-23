<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface {
	private $defaultLocale;

	public function __construct($defaultLocale = 'en') {
		$this->defaultLocale = $defaultLocale;
	}

	/**
	 * @return array
	 */
	public static function getSubscribedEvents() {
		return array(
			// must be registered before (i.e. with a higher priority than) the default Locale listener
			KernelEvents::REQUEST => array(array('onKernelRequest', 20)),
		);
	}

	/**
	 * @param GetResponseEvent $event
	 */
	public function onKernelRequest(GetResponseEvent $event): void{
		$request = $event->getRequest();
		// if (!$request->hasPreviousSession()) {
		// 	return;
		// }

		// try to see if the locale has been set as a _locale routing parameter
		if ($locale = $request->attributes->get('_locale')) {
			$request->getSession()->set('_locale', $locale);
			$response = new Response();
			$cookie = new Cookie('_locale', $locale);
			$response->headers->setCookie($cookie);
		} else {
			// if no explicit locale has been set on this request, use one from the session
			// $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
			$lang_session = $request->getSession()->get('_locale');
			$lang_cookies = $request->cookies->get('_locale');
			if ($lang_session != null) {
				$request->setLocale($lang_session);
			} elseif ($lang_cookies != null) {
				$request->setLocale($lang_cookies);
			} else {
				$request->setLocale($this->defaultLocale);
			}
		}
	}
}