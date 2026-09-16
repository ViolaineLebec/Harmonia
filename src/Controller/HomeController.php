<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(AlbumRepository $albumRepository): Response
    {
        $albums = $albumRepository->findBY(['type' => 'album']);
        $EPs = $albumRepository->findBy(['type' => 'EP']);
        $singles = $albumRepository->findBy(['type' => 'single']);

        $user = $this->getUser();
        dump($user);

        return $this->render('home/index.html.twig', [
            'albums' => $albums,
            'EPs' => $EPs,
            'singles' => $singles,
        ]);
    }
}
