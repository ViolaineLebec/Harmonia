<?php

namespace App\Factory;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentObjectFactory<Album>
 */
final class AlbumFactory extends PersistentObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Album::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        $coversArray = ['cover1.jpg','cover2.jpg','cover3.jpg','cover4.jpg','cover5.jpg','cover6.jpg','cover7.jpg','cover8.jpg','cover9.jpg','cover10.jpg'];
        $typesArray = ['single','EP','album'];

        return [
            'artist' => ArtistFactory::random(),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'releaseDate' => self::faker()->dateTime(),
            'title' => self::faker()->text(80),
            'type' => self::faker()->randomElement($typesArray),
            'imagePath' => "uploads/" . self::faker()->randomElement($coversArray),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Album $album): void {})
        ;
    }
}
