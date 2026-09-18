<?php

// Liste des artistes (route : /artist)
// Ajouter un artist (route : /artist-add)
// modifier un artsit (route : /artist-edit/{id})

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArtistController extends AbstractController
{
    #[Route('/artists', name: 'app_artists')]
    public function index(ArtistRepository $artistRepository): Response
    {
        $artists = $artistRepository->findAll();

        return $this->render('artist/index.html.twig', [
            'artists' => $artists,
        ]);
    }

    #[Route('/artist/{id}', name: 'app_artist')]
    public function artist($id, ArtistRepository $artistRepository): Response
    {
        $artist = $artistRepository->find($id);

        return $this->render('artist/artist.html.twig', [
            'artist' => $artist,
        ]);
    }

    #[Route('/artist-add', name: 'app_artist_add')]
    public function createArtist(EntityManagerInterface $em, Request $request): Response
    {
        $newArtist = new Artist();
        $form = $this->createForm(ArtistType::class, $newArtist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newArtist->setCreatedAt(new \DateTimeImmutable());
            $em->persist($newArtist);
            $em->flush();
        }


        return $this->render('artist/addArtist.html.twig', [
            'formArtist' => $form->createView(),
        ]);
    }

    #[Route('/artist-edit/{id}', name: 'app_artist_edit')]
    public function editArtist($id, ArtistRepository $artistRepository, EntityManagerInterface $em, Request $request): Response
    {
        $artist = $artistRepository->find($id);
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($artist);
            $em->flush();
        }

        return $this->render('artist/editArtist.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
