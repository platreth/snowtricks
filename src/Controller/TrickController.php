<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Picture;
use App\Entity\Video;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TrickController extends AbstractController
{
    /**
     * @Route("/member/trick/new", name="trick_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $images = [];
            foreach ($trick->getImages() as $image):

                $picture = new Picture();
                $picture->setName($trick->getName());
                $fileName = md5(uniqid()) .'.'.$image->guessExtension();
                try {
                  $image->move(
                      $this->getParameter('image_directory'),
                      $fileName
                  );
                  $picture->setPath($fileName);
                  $picture->setTrick($trick);
              } catch (FileException $e) {
                  // ... handle exception if something happens during file upload
              }
              array_push($images, $picture);
            endforeach;
              $trick->setImages($images);

            $videos = [];
            foreach ($trick->getVideos() as $vid):
                $video = new Video();
                $video->setName($trick->getName());
                $fileName = md5(uniqid()) .'.'.$vid->guessExtension();
                try {
                    $vid->move(
                        $this->getParameter('image_directory'),
                        $fileName
                    );
                    $video->setPath($fileName);
                    $video->setTrick($trick);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $trick->addVideos($videos);
            endforeach;


            $entityManager = $this->getDoctrine()->getManager();
            $trick->setDateCreate(new \DateTime());
            $trick->setAuthor($this->getUser());
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('app_homepage_index');
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/member/trick/{id}", name="trick_show", methods={"GET"})
     */
    public function show(Trick $trick): Response
    {
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/member/trick/{id}/edit", name="trick_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Trick $trick): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_homepage_index', [
                'id' => $trick->getId(),
            ]);
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/member/trick/{id}", name="trick_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Trick $trick): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trick);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_homepage_index');
    }
}
