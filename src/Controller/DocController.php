<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DocController extends AbstractController {

	/**
	 * @Route("/doc", name="doc_index", methods="GET")
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request): Response{
		return $this->render('documentation/doc.html.twig');
	}
}
