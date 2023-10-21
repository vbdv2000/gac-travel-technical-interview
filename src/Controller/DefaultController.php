<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/index', name: 'app_default')]
    public function index(): Response
    {
        return $this->render('index.html', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
