<?php

namespace App\Controller;

use App\Repository\PlaylistRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(PlaylistRepository $playlistRepository): Response
    {
        $user = $this->getUser();
        $playlists = $playlistRepository->findBy(['user' => $user]);
        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        dump($user);

        return $this->render('profile/profile.html.twig', [
            'playlists' => $playlists,
        ]);
    }
    // }
}
