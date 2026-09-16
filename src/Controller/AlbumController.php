<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\TrackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AlbumController extends AbstractController
{
    #[Route('/album/{id}', name: 'app_album')]
    public function index($id, AlbumRepository $albumRepository): Response
    {
        $album = $albumRepository->find($id);
        if($album === NULL){
           return $this->redirectToRoute('app_home');
        }
        dump($album);
        return $this->render('album/index.html.twig', [
            'album' => $album,
        ]);
    }
}
