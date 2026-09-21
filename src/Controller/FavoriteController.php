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
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function handleFavorite($id, EntityManagerInterface $em, TrackRepository $trackRepository, FavoriteRepository $favoriteRepository): JsonResponse
    {
        $user = $this->getUser();
        // ca on garde c'est bien, si le user est pas connecté => erreur
        if (!$user) {
            return $this->json(['message' => 'Non autorisé'], 401);
        }

        //on récupere la track par son id
        $track = $trackRepository->find($id);

        //on récupere le favoris en base de données en fonction du user et de la track
        $favorite = $favoriteRepository->findOneBy(['user' => $user, 'track' => $track]);

        // si jamais on a un favorite déjà en base de données on le supprime
        if ($favorite !== null) {
            $em->remove($favorite);
            $em->flush();

            //je passe cette variable à false au cas ou je l'ai supprimé
            $isCreated = false;
        } else {

            // sinon on en créer un nouveau
            $newFavorite = new Favorite();
            $newFavorite->setUser($user);
            $newFavorite->setTrack($track);
            $newFavorite->setCreatedAt(new DateTimeImmutable());
            $em->persist($newFavorite);
            $em->flush();

            // je passe cette variable à true ou cas ou je viens de le créer
            $isCreated = true;
        }


        // je renvoi du json à javascript
        return $this->json([

            // je renvoi la variable du dessus au javascript, la variable va me dire si c'est une création ou une suppression
            'isCreated' => $isCreated,
        ]);
    }
}
