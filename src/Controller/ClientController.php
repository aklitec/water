<?php

namespace App\Controller;


use App\Entity\Client;
use App\Entity\WaterMeter;
use App\Form\Client\ClientFinderType;
use App\Form\Client\ClientType;
use App\Form\WaterMeter\WaterMeterType;
use App\Repository\ClientRepository;
use App\Repository\WaterMeterRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/client")
 */
class ClientController extends AbstractController
{
    /**
     * @Route("/", defaults={"page": "1"} , name="client_index", methods={"GET"})
     * @Route("{id}/page/{page<[1-9]\d*>}", name="client_index_paginated", methods="GET")
     * @param Request $request
     * @param ClientRepository $clientRepository
     * @param int $page
     * @return Response
     */
    public function index(Request $request, ClientRepository $clientRepository, int $page): Response
    {
        $form = $this->createForm(ClientFinderType::class, new Client());
        $form->handleRequest($request);

        $getArgs= $request->query->get('client_finder');
        $clients = $getArgs ? $clientRepository->findMatchedClients($getArgs, $page) : $clientRepository->findLatestClients($page);


        return $this->render('client/index.html.twig', [
            'clients' => $clients,
            'form' => $form->createView(),
        ]);
    }




    /**
     * @Route("/new", name="client_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/water_meter/new", name="new_water_meter_to_current_client", methods={"GET","POST"})
     * @param Client $client
     * @param Request $request
     * @return Response
     */
    public function newWMeter(Client $client ,Request $request): Response
    {

        $waterMeter = new WaterMeter();
        $form = $this->createForm(WaterMeterType::class, $waterMeter);

        $form->remove("active");
        $form->remove("client");
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $waterMeter->setAddress($waterMeter->getAddress());
            $waterMeter->setClient($client);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($waterMeter);


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
     * @Route("/{id}", name="client_show", methods={"GET"})
     * @param Client $client
     * @param WaterMeterRepository $waterMeterRepository
     * @param Request $request
     * @return Response
     */
    public function show(Client $client , WaterMeterRepository $waterMeterRepository , Request $request): Response
    {

        $args = $client->getId();
        $waterMeters = $waterMeterRepository->findAllWaterMetersByClient($args);
        return $this->render('client/show.html.twig', [
            'waterMeters'=>$waterMeters,
            'client' => $client,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="client_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Client $client
     * @return Response
     */
    public function edit(Request $request, Client $client): Response
    {

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_show',['id' => $client->getId()]);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="client_delete", methods={"DELETE"})
     * @param Request $request
     * @param Client $client
     * @param WaterMeterRepository $waterMeterRepository
     * @return Response
     */
    public function delete(Request $request, Client $client, WaterMeterRepository $waterMeterRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $wm= $waterMeterRepository->findWmByClient($client->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $client->setDeleted(true);
            if(!empty($wm)){
                foreach ($wm as $waterMeter){
                    $waterMeter->setDeleted(true);
                }
            }


            $entityManager->flush();
        }

        return $this->redirectToRoute('client_index');
    }





}
