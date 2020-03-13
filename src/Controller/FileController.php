<?php
namespace App\Controller;

use App\Entity\File;
use App\Entity\Trick;
use App\Form\AddPictureType;
use App\Form\AddVideoType;
use App\Form\TrickEditType;
use App\Repository\TrickRepository;
use App\Services\FileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class FileController extends AbstractController {

    /**
     * @Route("/member/file/delete/{id}", name="file_delete", methods={"GET"})
     * @param FileManager $fileManager
     * @param Request $request
     * @param File $file
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(FileManager $fileManager, Request $request, File $file, EntityManagerInterface $entityManager): Response
    {
        $trick = $file->getTrickVideo();
        if ($trick == null) {
            $trick = $file->getTrickImage();
        }
        $fileManager->deleteFile($file);

        $entityManager->remove($file);
        $entityManager->flush();
        $this->addFlash('success', 'Le fichier a bien été supprimé');
        return $this->redirectToRoute('trick_show', array('slug' => $trick->getSlug()));
    }


    /**
     * @Route("/member/file/addImage/{id}", name="add_image", methods={"POST", "GET"})
     * @param FileManager $fileManager
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Trick $trick
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function addImage(FileManager $fileManager, Request $request, EntityManagerInterface $entityManager, Trick $trick) {

        $form = $this->createForm(AddPictureType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $picture = $fileManager->upload($request->files->get('add_picture')['file'], $trick->getName(),  'picture', 'picture', 'setTrickImage', $trick);
            $trick->addImage($picture);
            $entityManager->persist($picture);
            $entityManager->flush();

            $this->addFlash('success', 'L\'image a bien été ajoutée');
            return $this->redirectToRoute('trick_show', array('slug' => $trick->getSlug()));
        }
        return $this->render('trick/editFile.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/member/file/addVideo/{id}", name="add_video", methods={"POST", "GET"})
     * @param FileManager $fileManager
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Trick $trick
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function addVideo(FileManager $fileManager, Request $request, EntityManagerInterface $entityManager, Trick $trick) {
        $form = $this->createForm(AddVideoType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $video = $fileManager->upload($request->files->get('add_video')['file'], $trick->getName(),  'video', 'video', 'setTrickVideo', $trick);
            $trick->addImage($video);
            $entityManager->persist($video);
            $entityManager->flush();

            $this->addFlash('success', 'La vidéo a bien été ajoutée');
            return $this->redirectToRoute('trick_show', array('slug' => $trick->getSlug()));
        }
        return $this->render('trick/editFile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}