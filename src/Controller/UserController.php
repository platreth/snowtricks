<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\User;
use App\Form\User1Type;
use App\Service\FileUploader;
use App\Services\FileManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, FileManager $fileUpload): Response
    {
        $user = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();

        if ($user->getPicture() != null) {
            $fileUpload->deleteFile($user->getPicture());
            $entityManager->remove($user->getPicture());
        }

        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPicture = $entityManager->getRepository(File::class)->findOneBy(array('userImage' => $user->getId()));
            if ($oldPicture != null) {
                $fileUpload->deleteFile($oldPicture);
                $entityManager->remove($oldPicture);
            }

            $a = $fileUpload->upload($user->getPicture(), 'photo de profil de ' . $user->getPseudo(), 'file', 'image', 'setUserImage', $user);
            $user->setPicture($a);
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
