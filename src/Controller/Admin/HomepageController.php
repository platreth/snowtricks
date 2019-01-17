<?php 
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin") */
class HomepageController extends Controller {

    /**
     * @Route("/")
     */
    public function index() {
        return $this->render('admin/homepage/index.html.twig', ['mainNavAdmin' => true, 'title' => 'Espace Admin']);
    }

}