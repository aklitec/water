<?php

namespace App\Controller;

use App\Entity\Consumption;
use App\Entity\WaterMeter;
use App\Form\ConsumptionType;
use App\Repository\ConsumptionRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/consumption")
 *
 */
class ConsumptionController extends AbstractController
{
    /**
     * @Route("/{id}",name="consumption_index", methods={"GET"})
     * @param WaterMeter $waterMeter
     * @param Request $request
     * @param ConsumptionRepository $consumptionRepository
     * @return Response
     */
    public function index(WaterMeter $waterMeter,Request $request,ConsumptionRepository $consumptionRepository , int $page = 1): Response

    {

        $year = $consumptionRepository->findLatestConsumption($waterMeter->getId());
        return $this->render('consumption/index.html.twig', [
            'waterMeter'=>$waterMeter,
            'year'=>$year,
        ]);
    }



    /**
     * @Route("/{id}/new", name="consumption_new", methods={"GET","POST"})
     * @param ConsumptionRepository $consumptionRepository
     * @param WaterMeter $waterMeter
     * @param Request $request
     * @return Response
     */
    public function new(ConsumptionRepository $consumptionRepository,WaterMeter $waterMeter,Request $request): Response
    {
        $consumption = new Consumption();

        $lastConsumption = $consumptionRepository->findLatestConsumption($waterMeter->getId());

        if($lastConsumption==!null){
            $pervRecord = $lastConsumption->getCurrentRecord();
        }else(
            $pervRecord = 0
        );


        $consumption->setPreviousRecord($pervRecord);
        $form = $this->createForm(ConsumptionType::class, $consumption);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
                $consumption->setPreviousRecord($pervRecord);
                $consumption->setConsumption($consumption->getCurrentRecord()-$pervRecord);
                $consumption->setCost($consumption->getCostPerMeterCube()*$consumption->getConsumption());
                $consumption->setWaterMeter($waterMeter);
                $entityManager->persist($consumption);
                $entityManager->flush();;
                $entityManager->clear();
            return $this->redirectToRoute('consumption_index',['id'=>$waterMeter->getId()]);
        }

        return $this->render('consumption/new.html.twig', [
           // 'consumption' => $consumption,
            'form' => $form->createView(),
            //'lastConsumption'=>$lastConsumption
        ]);
    }



    /**
     * @Route("/{id}/edit", name="consumption_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Consumption $consumption
     * @return Response
     */
    public function edit(Request $request, Consumption $consumption): Response
    {
        $form = $this->createForm(ConsumptionType::class, $consumption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('consumption_index');
        }

        return $this->render('consumption/edit.html.twig', [
            'consumption' => $consumption,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/show/{item}", name="consumption_show", methods={"GET"})
     * @param Consumption $consumption
     * @param ConsumptionRepository $consumptionRepository
     * @param WaterMeter $waterMeter
     * @param $page
     * @return Response
     */
    public function show(Consumption $consumption, ConsumptionRepository $consumptionRepository, WaterMeter $waterMeter, $page): Response

    {
        $consumptions = $consumptionRepository->findAll();
        $year = $consumptionRepository->findLatestConsumption($waterMeter->getId());

//      $consumptions = $consumptionRepository->findAll();

        return $this->render('consumption/show.html.twig', [
            'waterMeter' => $waterMeter,
            'year' =>$year,
            'consumptions'=>$consumptions
        ]);
    }

    /**
     * @Route("/{id}", name="consumption_delete", methods={"DELETE"})
     * @param Request $request
     * @param Consumption $consumption
     * @return Response
     */
    public function delete(Request $request, Consumption $consumption): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consumption->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($consumption);
            $entityManager->flush();
        }

        return $this->redirectToRoute('consumption_index');
    }

    /**
     * @Route("/{id}/api/search/{item}", name="consumption_query", methods={"GET"})
     * @param ConsumptionRepository $cr
     * @param WaterMeter $waterMeter
     * @param string $item
     * @return Response
     */
    public function queryConsumption(ConsumptionRepository $cr, WaterMeter $waterMeter,$item=''): Response
    {
        $consumptions = $cr->findAllConsumptions($waterMeter->getId(),$item);
       // dd($consumptions);
        $response = array();
        //dump($consumptions);
        foreach ($consumptions as $r){
            array_push($response,
                [   'code'=>$r->getCode(),
                    'id'=>$r->getId(),
                    'date'=>date_format($r->getDate(),'Y-m-d'),
                    'water_meter_id'=>$waterMeter->getId(),
                    'previous_record'=>$r->getPreviousRecord(),
                    'current_record'=>$r->getCurrentRecord(),
                    'consumption'=>$r->getConsumption(),
                    'coste'=>$r->getCost(),
                    'status'=>$r->getStatus()
                ]);
        }
        //dd($response);
        return new JsonResponse($response);
    }

}
