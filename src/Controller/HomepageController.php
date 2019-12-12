<?php
namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController {

    /**
     * @Route("/", name="app_homepage_index", methods={"GET"})
     */
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findByTrickAjax(0, 4),
        ]);
    }

    /**
     * @Route("/trickAjax/{first}", name="app_homepage_trickajax", methods={"GET"})
     * @param TrickRepository $trickRepository
     * @param int $first
     * @return JsonResponse
     */
    public function loadMoreTrick(TrickRepository $trickRepository, int $first) {
        return new JsonResponse($trickRepository->findByTrickAjax($first, 4));
    }

}