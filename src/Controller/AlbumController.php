<?php

// Depuis la page d'un album, bouton "ajouter" (pour ajouter une musique) qui va vers la route /add-track/{album_id}

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Track;
use App\Form\AlbumType;
use App\Form\TrackType;
use App\Repository\AlbumRepository;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class AlbumController extends AbstractController
{
    #[Route('/album/{id}', name: 'app_album')]
    public function index($id, AlbumRepository $albumRepository): Response
    {
        $album = $albumRepository->find($id);
        if ($album === NULL) {
            return $this->redirectToRoute('app_home');
        }
        dump($album);
        return $this->render('album/index.html.twig', [
            'album' => $album,
        ]);
    }

    #[Route('/album-add', name: 'app_album_add')]
    public function addAlbum(
        EntityManagerInterface $em,
        //slugger sert à formater les strings en retirant les espaces, les accents, etc.
        SluggerInterface $slugger,
        Request $request,
        #[Autowire('%kernel.project_dir%/public/uploads')] string $uploadsDirectory
    ): Response {

        $newAlbum = new Album();
        $form = $this->createForm(AlbumType::class, $newAlbum);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            //getData() récupère le fichier entré dans le champ 'image' du formulaire
            $image = $form->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                $image->move($uploadsDirectory, $newFilename);

                $newAlbum->setImagePath('uploads/' . $newFilename);
            }

            $newAlbum->setCreatedAt(new \DateTimeImmutable());

            $em->persist($newAlbum);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('album/addAlbum.html.twig', [
            'newAlbum' => $newAlbum,
            'form' => $form,
        ]);
    }


    #[Route('/album/{id}/add-track', name: 'app_add_track')]
    public function addTrackToAlbum($id, AlbumRepository $albumRepository, EntityManagerInterface $em, Request $request): Response
    {
        $album = $albumRepository->find($id);
        $newTrack = new Track();
        $form = $this->createForm(TrackType::class, $newTrack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newTrack->setCreatedAt(new \DateTimeImmutable());
            $newTrack->setAlbum($album);
            $newTrack->setArtist($album->getArtist());
            // $newTrack->setUser($this->getUser());
            $em->persist($newTrack);
            $em->flush();

            return $this->redirectToRoute('app_album', ['id' => $album->getId()]);
        }


        return $this->render('track/addTrack.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
