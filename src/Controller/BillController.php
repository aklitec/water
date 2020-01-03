<?php

namespace App\Controller;

use App\Entity\Bill;
use App\Form\bill\BillFinderType;
use App\Form\bill\ReleaseBillType;
use App\Form\bill\BillType;
use App\Repository\BillRepository;
use App\Repository\ConsumptionRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
/**
 * @Route("/bill")
 */
class BillController extends Controller
{
    /**
     * @Route("/", defaults={"page": "1"}, name="bill_index", methods={"GET","POST"})
     * @Route("/page/{page<[1-9]\d*>}", name="client_index_paginated", methods="GET")
     * @param BillRepository $billRepository
     * @param int $page
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function index(BillRepository $billRepository, int $page,Request $request): Response
    {
        $form = $this->createForm(BillFinderType::class, new Bill());
        $form->handleRequest($request);

        $getArgs= $request->query->get('bill_finder');

        $bills = $billRepository->findMatchedBillMeters($getArgs,$page);

        return $this->render('bill/index.html.twig', [
            'bills' => $bills,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/release_bills", name="print_bills", methods={"GET","POST"})
     * @param ConsumptionRepository $consumptionRepository
     * @param Request $request
     * @return Response
     */
    public function addToPrint(ConsumptionRepository $consumptionRepository, Request $request): Response
    {
        $form = $this->createForm(ReleaseBillType::class);

        $form->handleRequest($request);
        $getArgs= $request->query->get('release_bill');
        $city = $getArgs['city'];
        $month = $getArgs['month'];
        $year = $getArgs['year'];
        $entityManager = $this->getDoctrine()->getManager();

        if($month && $year && $city){
            $bills = $consumptionRepository->findAllWaterMetersForBilling($month,$year,$city);

            if(empty($bills)){
                    $this->addFlash('warning','there is not bills to be release for this criteria : ( city: '.$city.' and date :'.$year.'-'.$month.'!)');
                return $this->redirectToRoute('print_bills');
            }

            $arr = array();
            $newBills = array();
            $billsEnd=end($bills);
            try {
                foreach ($bills as $key=>$b){
                        $wmNumber =  $b->getWaterMeter()->getWmNumber();
                        $fullName = $b->getWaterMeter()->getClient()->getFullName();
                        $cin = $b->getWaterMeter()->getClient()->getCin();
                        $address= $b->getWaterMeter()->getAddress();
                        $billCode = $b->getId();
                        $consumptionDate = $b->getDate();
                        $oldConsumption=$b->getPreviousRecord();
                        $newConsumption=$b->getCurrentRecord();
                        $printDate =new \DateTime('now');
                        $consumption=$b->getConsumption();
                        $cost = $b->getCost();
                        $client= $b->getWaterMeter()->getClient();
                        // tranche calculation
                        //==================================================
                        $tranche1 = $consumption >= 5 ? 5 : $consumption; //4
                        $tranche2 = $consumption - $tranche1 >= 5 ? 5: 0; //0
                        $tranche3 = $consumption - $tranche1 - $tranche2;
                        //==================================================
                        $bill = new Bill();
                        $bill->setCode($wmNumber);
                        $bill->setFullName($fullName);
                        $bill->setCin($cin);
                        $bill->setPrintDate($printDate);
                        $bill->setCost($cost);
                        $bill->setConsumption($consumption);
                        $bill->setConsumptionDate($consumptionDate);
                        $bill->setPreviousRecord($oldConsumption);
                        $bill->setNewRecord($newConsumption);
                        $bill->setWaterMeter($b->getWaterMeter());
                        $bill->setClient($client);
                        $entityManager->persist($bill);
                        array_push($arr,['tranche1'=>$tranche1,'wmNumber'=>$wmNumber,'fullName'=>$fullName,'cin'=>$cin,'address'=>$address,'billCode'=>$billCode,'consumptionDate'=>$consumptionDate,'oldConsumption'=>$oldConsumption,'newConsumption'=>$newConsumption,'printDate'=>$printDate,'consumption'=>$consumption,'cost'=>$cost,]);

                        if(count($arr)==6||$billCode==$billsEnd->getId()){
                            array_push($newBills,$arr);
                            $arr = array();
                        }
                    }

                    $entityManager->flush(); //Persist objects that did not make up an entire batch
                    $entityManager->clear();
                    $this->addFlash('success','bills have been successfully released');
                    $date = new \DateTime('now');


                    $html= $this->renderView('bill/bill.html.twig', array(
                        'arrayBills'=>$newBills,
                    ));

                    return new PdfResponse(
                        $this->get('knp_snappy.pdf')->getOutputFromHtml($html), "bill_".$date->format('Y-m-d').".pdf"
                    );

                }catch ( Exception $e){
                       $this->addFlash('error','bills have already been released for the city or area of '.$city.' for this date :( '.$year.'-'.$month.')');
                }

        }


        return $this->render('bill/printIndex.html.twig', [
            'form'=>$form->createView(),
        ]);
    }




    /**
     * @Route("/new", name="bill_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $bill = new Bill();
        $form = $this->createForm(BillType::class, $bill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bill);
            $entityManager->flush();

            return $this->redirectToRoute('bill_index');
        }

        return $this->render('bill/new.html.twig', [
            'bill' => $bill,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bill_show", methods={"GET"})
     * @param Bill $bill
     * @param BillRepository $billRepository
     * @return Response
     */
    public function show(Bill $bill,BillRepository $billRepository): Response
    {

        $billOwner = $bill->getClient();
        $billWaterMeter = $bill->getWaterMeter();
        $totalCost = $billRepository->getBillsByClient($billOwner->getId(),$arg='cost');
        $totalConsumption = $billRepository->getBillsByClient($billOwner->getId(),'consumption');

        return $this->render('bill/show.html.twig', [
            'bill' => $bill,
            'billOwner'=>$billOwner,
            'waterMeter'=>$billWaterMeter,
            'totalCost' =>$totalCost,
            'totalConsumption'=>$totalConsumption
        ]);
    }

    /**
     * @Route("/{id}/edit", name="bill_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Bill $bill): Response
    {
        $form = $this->createForm(BillType::class, $bill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bill_index');
        }

        return $this->render('bill/edit.html.twig', [
            'bill' => $bill,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bill_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Bill $bill): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bill->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bill);
            $entityManager->flush();
        }

        return $this->redirectToRoute('bill_index');
    }
}
