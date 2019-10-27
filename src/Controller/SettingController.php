<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class SettingController extends AbstractController {
	/**
	 * @Route("/setting/locale/{locale}", name="setting_locale")
	 */
	public function index(Request $request, SessionInterface $session, $locale) {
		$next = $request->query->get('next') != null ? $request->query->get('next') : "/";
		$lang = "fr";
		switch ($locale) {
		case 'ar_MA':
			$lang = "ar_MA";
			break;
		case 'fr':
			$lang = "fr";
			break;
		case 'en':
			$lang = "en";
			break;
		default:
			$lang = "en";
		}
		$session->set('_locale', $lang);
		$response = new Response();
		$cookie = new Cookie('_locale', $lang);
		$response->headers->setCookie($cookie);
		$response->send();
		return $this->redirect($next);
	}
}
