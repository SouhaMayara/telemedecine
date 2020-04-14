<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PostType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ForumController extends AbstractController
{
  /**
   * @Route("/forumpost/{id}", name="forumpost")
   */
  public function forumPost($id,Request $request)
  {
      $post = new Post();
      $form = $this->createForm(PostType::class, $post);
     // , [
     //   'action' => $this->generateUrl('forum')
      //]);

      //$form->handleRequest($request);
     // if($form->isSubmitted() && $form->isValid()){
      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        //$id = $request->attributes->get('id');

        $user=$this->getDoctrine()->getRepository(User::class)->find($id);
      
        $post->setEmailOwner($user->getEmail());
        $post->setType("forum");
        $post->setNbComments("0");

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);
        $entityManager->flush();
        }
      return $this->render('forum/makeSubject.html.twig', [
          'form' => $form->createView()
      ]);
  }
}
