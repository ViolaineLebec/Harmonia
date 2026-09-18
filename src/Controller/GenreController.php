<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GenreController extends AbstractController
{
    #[Route('/genre/{id}', name: 'app_genre')]
    public function genre($id, GenreRepository $genreRepository): Response
    {
        $genre = $genreRepository->find($id);
        if ($genre === NULL) {
            return $this->redirectToRoute('app_home');
        }


        return $this->render('genre/genre.html.twig', [
            'genre' => $genre,
        ]);
    }
}
