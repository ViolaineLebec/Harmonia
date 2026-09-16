<?php

namespace App\DataFixtures;

use App\Factory\AlbumFactory;
use App\Factory\ArtistFactory;
use App\Factory\FavoriteFactory;
use App\Factory\GenreFactory;
use App\Factory\HistoryFactory;
use App\Factory\PlaylistFactory;
use App\Factory\TrackFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $genresArray = ['rock','reggae','classique', 'jazz', 'pop', 'hip-hop', 'electro', 'blues', 'metal', 'funk', 'soul', 'country', 'folk', 'punk', 'disco', 'rnb', 'rap', 'house', 'techno', 'ambient', 'salsa', 'bossa nova', 'ska', 'gospel', 'grime', 'dubstep', 'afrobeats', 'drill', 'synthwave'];
        

        ArtistFactory::createMany(50);
        UserFactory::createMany(500);
        AlbumFactory::createMany(50);
        foreach($genresArray as $value){
            GenreFactory::createOne([
                'label' => $value
            ]);
        }
        TrackFactory::createMany(100);
        PlaylistFactory::createMany(100);
        FavoriteFactory::createMany(50);
        HistoryFactory::createMany(200);

        $manager->flush();
    }
}
