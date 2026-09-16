<?php

namespace App\Controller;

use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PlaylistController extends AbstractController
{
    #[Route('/playlists', name: 'app_playlists')]
    public function playlists(PlaylistRepository $playlistRepository): Response
    {
        $user = $this->getUser();
        $playlists = $playlistRepository->findBy(['user' => $user]);

        return $this->render('playlist/playlists.html.twig', [
            'playlists' => $playlists,
        ]);
    }

    #[Route('/playlist/{id}', name: 'app_playlist')]
    public function playlist($id, PlaylistRepository $playlistRepository): Response
    {
        $playlist = $playlistRepository->find($id);

        return $this->render('playlist/playlist.html.twig', [
            'playlist' => $playlist,
        ]);
    }
}
