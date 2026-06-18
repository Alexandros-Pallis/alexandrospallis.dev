<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        return $this->render('homepage/index.html.twig', [
            'menu' => [
                ['title' => 'Home', 'url' => $this->generateUrl('app_homepage'), 'icon' => 'ti-home', 'active' => false],
                ['title' => 'Skills', 'url' => '#', 'icon' => 'ti-terminal-2', 'active' => false],
                ['title' => 'Work', 'url' => '', 'icon' => 'ti-code', 'active' => true],
                ['title' => 'About', 'url' => '#', 'icon' => 'ti-user-circle', 'active' => false],
            ],
        ]);
    }
}
