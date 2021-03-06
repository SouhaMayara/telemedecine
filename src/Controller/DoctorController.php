<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Doctor;
use App\Entity\User;
use App\Form\DoctorType;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Security\UsersAuthenticator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;


class DoctorController extends AbstractController
{
    /**
     * @Route("/doctor/{id}", name="doctor")
     */
    public function index($id ,Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UsersAuthenticator $authenticator): Response
    {
        $doctor=new Doctor();
        $form = $this->createForm(DoctorType::class, $doctor);
        //$form->handleRequest($request);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $user=$this->getDoctrine()->getRepository(User::class)->find($id);

          // création d'un docteur à partir de ses infos déja existantes dans la BD (table user) + nouveau données
          $doctor->setName($user->getName());
          $doctor->setLastname($user->getLastname());
          $doctor->setPhone($user->getPhone());
          $doctor->setB(false);
          $doctor->setEmail($user->getEmail());

          //upload fichier (image)
            $uploadedFile = $form['image']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/img/doctor';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $user=$this->getDoctrine()->getRepository(User::class)->find($id);

            $doctor->setImg($newFilename);
            $doctor->setName($user->getName());
            $doctor->setLastname($user->getLastname());
            $doctor->setPhone($user->getPhone());
            $doctor->setB(false);
            $doctor->setEmail($user->getEmail());

            //ajout dans la BD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($doctor);
            $entityManager->flush();

            // do anything else you need here, like send an email

           /* return $guardHandler->authenticateUserAndHandleSuccess(
                $doctor,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );*/
        }
        return $this->render('RegisterAsDoctor.html.twig', [
            'form' => $form->createView(),
        ]);
    }

  /**
     * @Route("/finddoctor/{id}")
     */
  /* public function findd($id )
    {
        $j=$this->getDoctrine()->getRepository(doctor::class)->find($id);
        return $this->render('security/profil.html.twig', ['doctor' =>$j
        ]);
    }*/


      /**
     * @Route("/listdoctors")
     */
    public function ListDoctor()
    {
      // affichage des docteurs
      
        $list=$this->getDoctrine()->getRepository(Doctor::class)->findAll();
        return $this->render('doctors.html.twig', [
            'list' =>$list
        ]);
    }


}
