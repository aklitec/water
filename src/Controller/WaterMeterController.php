<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\WaterMeter;
use App\Form\Client\ClientFinderType;
use App\Form\Client\ClientType;
use App\Form\WaterMeter\WaterMeterFinder;
use App\Form\WaterMeter\WaterMeterType;
use App\Repository\ConsumptionRepository;
use App\Repository\WaterMeterRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/water/meter")
 */
class WaterMeterController extends AbstractController
{
    /**
     * @Route("/", defaults={"page": "1"}, name="water_meter_index", methods={"GET"})
     * @Route("/page/{page<[1-9]\d*>}", name="client_index_paginated", methods="GET")
     * @param WaterMeterRepository $waterMeterRepository
     * @param Request $request
     * @param int $page
     * @return Response
     */
    public function index(WaterMeterRepository $waterMeterRepository, Request $request, int $page): Response
    {

        $form = $this->createForm(ClientFinderType::class, new Client());
        $form->handleRequest($request);

        $getArgs= $request->query->get('client_finder');

        $WaterMeters = $getArgs ? $waterMeterRepository->findMatchedWaterMeters($getArgs,$page) : $waterMeterRepository->findAllWaterMeters();

        return $this->render('water_meter/index.html.twig', [
            'WaterMeters' => $WaterMeters,
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="water_meter_new", methods={"GET","POST"})
     * @param Client $client
     * @param Request $request
     * @return Response
     */
    public function new(Client $client ,Request $request): Response
    {
        $waterMeter = new WaterMeter();
        $form = $this->createForm(WaterMeterType::class, $waterMeter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $waterMeter->setClient($client);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($waterMeter);
            $entityManager->flush();

            return $this->redirectToRoute('client_show',['id' => $client->getId()]);
        }

        return $this->render('water_meter/new.html.twig', [
            'water_meter' => $waterMeter,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="water_meter_show", methods={"GET"})
     * @param WaterMeter $waterMeter
     * @param ConsumptionRepository $consumptionRepository
     * @return Response
     */
    public function show(WaterMeter $waterMeter, ConsumptionRepository $consumptionRepository ): Response
    {


        return $this->render('water_meter/show.html.twig', [
            'waterMeter' => $waterMeter,
            ]);

    }


    /**
     * @Route("/{id}/edit", name="water_meter_edit", methods={"GET","POST"})
     * @param Request $request
     * @param WaterMeter $waterMeter
     * @return Response
     */
    public function edit(Request $request, WaterMeter $waterMeter): Response
    {

        $form = $this->createForm(WaterMeterType::class, $waterMeter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_show',['id'=>$waterMeter->getClient()->getId()]);
        }

        return $this->render('water_meter/edit.html.twig', [
            'waterMeter' => $waterMeter,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="water_meter_delete", methods={"DELETE"})
     * @param Request $request
     * @param WaterMeter $waterMeter
     * @return Response
     */
    public function delete(Request $request, WaterMeter $waterMeter): Response
    {
        if ($this->isCsrfTokenValid('delete'.$waterMeter->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($waterMeter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('water_meter_index');
    }
}
