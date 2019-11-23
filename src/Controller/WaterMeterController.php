<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\WaterMeter;
use App\Form\Client\ClientFinderType;
use App\Form\WaterMeter\WaterMeterFinder;
use App\Form\WaterMeter\WaterMeterType;
use App\Repository\ClientRepository;
use App\Repository\ConsumptionRepository;
use App\Repository\WaterMeterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/add", name="water_meter_add", methods={"GET","POST"})
     * @param ClientRepository $clientRepository
     * @param Request $request
     * @return Response
     */
    public function add(ClientRepository $clientRepository ,Request $request): Response
    {

        $waterMeter = new WaterMeter();

        $form = $this->createForm(WaterMeterType::class, $waterMeter);
        $form->remove("active");

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $id=$form->get("client")->getData();
            $client = $clientRepository->find((int)$id);
             $waterMeter->setAddress($waterMeter->getAddress());
             $waterMeter->setClient($client);
           $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($waterMeter);

                 $entityManager->flush();

                return $this->redirectToRoute('water_meter_index');
          }


           return $this->render('water_meter/new.html.twig', [
               'water_meter' => $waterMeter,
               'form' => $form->createView(),
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

        $form->remove("active");
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $waterMeter->setAddress($waterMeter->getAddress());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($waterMeter);
            $waterMeter->setClient($client);

            $entityManager->flush();

            return $this->redirectToRoute('client_show',['id'=>$client->getId()]);
        }

        return $this->render('water_meter/new.html.twig', [
            'water_meter' => $waterMeter,
            'form' => $form->createView(),
            'client'=> $client
        ]);
    }

    /**
     * Route("/new/client",name="water_meter_new_client", methods={"GET,POST"})
     * @param Request $request
     * @param ClientRepository $clientRepository
     * @return Response
     */
    public function searchClient(Request $request, ClientRepository $clientRepository):Response
    {

        $max = $request->get('max', 10);

        $query = $request->get('searchTerm', '');



        $response = array();
        $newQuery=trim($query);
        if(empty($newQuery)){
            $res = $clientRepository->findAll();
            foreach ($res as $r) {
                array_push($response, ['id' => $r->getId(), 'text' => $r->getFullName().' - (CIN: ' . $r->getCin().' )']);
            }

        }else{
            $results = $clientRepository->searchByQuery($query, $max);
            $res = $results->getResult();
            foreach ($res as $r) {
                array_push($response,['id' => $r->getId(), 'text' => $r->getFullName(). ' - (CIN: ' . $r->getCin().' )']);
            }
        }




        // return a json response

        return new JsonResponse($response);

    }

    /**
     * @Route("/{id}", defaults={"page"="1"}, name="water_meter_show", methods={"GET"})
     * @Route("/{id}/page/{page<[1-9]\d*>}", name="consumption_show_paginated", methods="GET")
     * @param WaterMeter $waterMeter
     * @param ConsumptionRepository $consumptionRepository
     * @param $page
     * @return Response
     */
    public function show(WaterMeter $waterMeter, ConsumptionRepository $consumptionRepository, $page ): Response
    {
        $consumptions = $consumptionRepository->findConsumptionByEachYear($waterMeter->getId(),$page);
        $year = $consumptionRepository->findLatestConsumption($waterMeter->getId(),$page);

        return $this->render('water_meter/show.html.twig', [
            'waterMeter' => $waterMeter,
            'year' =>$year,
            'consumptions'=>$consumptions
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
            //$entityManager->remove($waterMeter);
            $waterMeter->setDeleted(true);
            $entityManager->flush();
        }

        return $this->redirectToRoute('water_meter_index');
    }


}
