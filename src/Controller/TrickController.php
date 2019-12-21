<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Picture;
use App\Entity\Video;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use App\Services\FileManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TrickController extends AbstractController
{
    /**
     * @Route("/member/trick/new", name="trick_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileManager $filemanager): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $array_picture = array();
            $array_video = array();

            foreach ($trick->getImages() as $image):
                $picture = $filemanager->upload($image, $trick->getName(), 'picture', 'picture', 'setTrickImage', $trick);
                array_push($array_picture, $picture);
            endforeach;
            foreach ($trick->getVideos() as $vid):
                $video = $filemanager->upload($vid, $trick->getName(), 'video', 'video', 'setTrickVideo', $trick);
                array_push($array_video, $video);
            endforeach;

            $trick->setVideos($array_video);
            $trick->setImages($array_picture);


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
     * @Route("/member/trick/delete/{id}", name="trick_delete", methods={"DELETE"})
     */
    public function delete(FileManager $fileManager, Request $request, Trick $trick): Response
    {
            $entityManager = $this->getDoctrine()->getManager();

            foreach ($trick->getImages() as $image) {
                $fileManager->deleteFile($image);
                $trick->removeImage($image);
            }
            foreach ($trick->getVideos() as $video) {
                $fileManager->deleteFile($video);
            }
            $entityManager->remove($trick);
            $entityManager->flush();

        return $this->redirectToRoute('app_homepage_index');
    }
}
