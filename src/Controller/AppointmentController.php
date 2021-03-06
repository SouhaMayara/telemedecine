<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Form\AppointmentType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends AbstractController
{
    /**
     * @Route("/appointment", name="appointment")
     */
    public function index(Request $request): Response
    {
      // Création d'un rendez-vous
      $appointment=new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

       /* $list_Doctor=$this->getDoctrine()->getRepository(Doctor::class)->findAll();
        $list_Service=[];
        $doctors=[];
        foreach ($list_Doctor as $i){
            $j=0;
            $list_Service[$j]=$i->getSpeciality();
            //if($i->getSpeciality()===$service || $i->getSpeciality()==='general')
            {$doctors=$i->getName().' '.$i->getLastname();}
            $j=$j+1;
        }*/
        if ($form->isSubmitted() && $form->isValid()) {
           // if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

           // ajout à la BD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($appointment);
            $entityManager->flush();

        }
        return $this->render('appointment.html.twig', [
            'form' =>  $form->createView()
        ]);
    }

     /**
     * @Route("/listApp/{id}")
     */
    public function ListAppointment($id)
    {
      // affichage de la liste des rendez-vous pour un docteur
        $list=$this->getDoctrine()->getRepository(Appointment::class)->findBy(
            ['doctor'=>$id],['date'=>'asc']
        );
        return $this->render('listeAppointment.html.twig', [
            'list' =>$list//->createQueryBuilder('p')
                        //->orderBy('p.date', 'asc')
        ]);
    }

}
