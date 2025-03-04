<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeddingController extends AbstractController
{
    #[Route('/wedding', name: 'app_wedding')]
    public function index(): Response
    {
        return $this->render('wedding/index.html.twig', [
            'controller_name' => 'WeddingController',
        ]);
    }
}
