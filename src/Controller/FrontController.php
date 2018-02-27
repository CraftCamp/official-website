<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FrontController extends Controller {

    /**
     * @Route("/", name="homepage", methods={"GET"})
     */
    public function homepageAction() {
        return $this->render('front/homepage.html.twig');
    }
}
