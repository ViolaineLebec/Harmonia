<?php

namespace App\Controller;

use App\Repository\TrackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TrackController extends AbstractController
{
    #[Route('/track/{id}', name: 'app_track')]
    public function track($id, TrackRepository $trackRepository): Response
    {
        $track = $trackRepository->find($id);
        dump($track);
        if($track === NULL){
           return $this->redirectToRoute('app_home');
        }

        return $this->render('track/track.html.twig', [
            'track' => $track,
        ]);
    }
}
