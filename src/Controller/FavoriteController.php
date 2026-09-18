<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Entity\User;
use App\Repository\FavoriteRepository;
use App\Repository\TrackRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FavoriteController extends AbstractController
{
    #[Route('/favorites', name: 'app_favorites')]
    public function index(FavoriteRepository $favoriteRepository): Response
    {
        $user = $this->getUser();
        $favorites = $favoriteRepository->findBy(['user' => $user]);

        return $this->render('favorite/index.html.twig', [
            'favorites' => $favorites,
        ]);
    }

    #[Route('/handle-favorite/{id}', name: 'app_handle_favorite')]
    public function handleFavorite($id, EntityManagerInterface $em, TrackRepository $trackRepository, FavoriteRepository $favoriteRepository): Response
    {
        $user = $this->getUser();

        if ($user === null) {
            $this->redirectToRoute('app_login');
        }

        $track = $trackRepository->find($id);
        $favorite = $favoriteRepository->findOneBy(['user' => $user, 'track' => $track]);

        if ($favorite !== null) {
            // $user->removeFavorite($favorite);
            $em->remove($favorite);
            $em->flush();
        } else {

            $newFavorite = new Favorite();
            $newFavorite->setUser($user);
            $newFavorite->setTrack($track);
            $newFavorite->setCreatedAt(new DateTimeImmutable());
            $em->persist($newFavorite);
            $em->flush();
        }


        return $this->redirectToRoute('app_track', ['id' => $track->getId()]);
    }
}
