<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(Request $request)
    {
        // echo phpinfo();
        // die();

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'locale' => $request->getLocale(),
        ]);
    }
}
