<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\Picture;
use App\Entity\Video;
use App\Form\CommentType;
use App\Form\TrickEditCoverType;
use App\Form\TrickEditType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use App\Services\FileManager;
use App\Services\SlugifyService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TrickController extends AbstractController
{
    /**
     * @Route("/member/trick/new", name="trick_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileManager $filemanager, SlugifyService $slugify): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $array_picture = array();
            $array_video = array();

            $trick->setCover($filemanager->simpleUpload($trick->getCover(), 'picture'));

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

            $trick->setSlug($slugify->generateSlugify($trick->getName(), Trick::class));



            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Le trick ' . $trick->getName() . ' a bien été enregistré !'
            );


            return $this->redirectToRoute('app_homepage_index');
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/member/trick/{slug}", name="trick_show", methods={"GET", "POST"})
     * @param Trick $trick
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param CommentRepository $commentRepository
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function show(Trick $trick, Request $request, EntityManagerInterface $manager, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setTrick($trick);
            $comment->setUser($this->getUser());
            $trick->addComment($comment);

            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute('trick_show', array(
                'slug' => $trick->getSlug()
            ));
        }
        return $this->render('trick/show.html.twig', [
            'compteur' => $commentRepository->count(array('trick' => $trick->getId())),
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/member/trick/{slug}/edit", name="trick_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Trick $trick): Response
    {
        $form = $this->createForm(TrickEditType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute('trick_show', [
                'slug' => $trick->getSlug(),
            ]);
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/member/trick/{slug}/edit/cover", name="trick_edit_cover", methods={"GET","POST"})
     */
    public function editCover(Request $request, Trick $trick, FileManager $filemanager): Response
    {
        $form = $this->createForm(TrickEditCoverType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setCover($filemanager->simpleUpload($trick->getCover(), 'picture'));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('trick_show', [
                'slug' => $trick->getSlug(),
            ]);
        }

        return $this->render('trick/edit_cover.html.twig', [
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

    /**
     * @Route("/member/trick/ajaxComment/{slug}", name="trick_comment_ajax", methods={ "POST"})
     * @param Trick $trick
     * @param Request $request
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function ajaxComment(Trick $trick, Request $request, CommentRepository $commentRepository): Response
    {
        $start = $_POST['start'];
        $id = $_POST['id'];
        return new JsonResponse($commentRepository->getComment($id, $start, 5));
    }
}
