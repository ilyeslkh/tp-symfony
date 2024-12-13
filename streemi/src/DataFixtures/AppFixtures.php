<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Language;
use App\Entity\User;
use App\Entity\Media;
use App\Entity\Movie;
use App\Entity\Serie;
use App\Entity\Subscription;
use App\Entity\Playlist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Add Categories
        $categories = ['Action', 'Adventure', 'Drama', 'Comedy'];
        $categoryEntities = [];
        foreach ($categories as $name) {
            $category = new Category();
            $category->setNom($name);
            $manager->persist($category);
            $categoryEntities[] = $category;
        }

        // Add Languages
        $languages = ['English', 'French', 'Spanish', 'German'];
        $languageEntities = [];
        foreach ($languages as $name) {
            $language = new Language();
            $language->setNom($name);
            $language->setCode(substr($name, 0, 2));
            $manager->persist($language);
            $languageEntities[] = $language;
        }

        // Add Users
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        // Add Subscriptions
        $subscription = new Subscription();
        $subscription->setName('Premium');
        $subscription->setPrice(20);
        $subscription->setDuration(30); // in days
        $manager->persist($subscription);

        // Add Media
        for ($i = 1; $i <= 5; $i++) {
            $movie = new Movie();
            $movie->setTitle("Movie $i");
            $movie->setPopularity(mt_rand(1, 100));
            $movie->setDescription("Description for Movie $i");
            $movie->addCategory($categoryEntities[array_rand($categoryEntities)]);
            $movie->addLanguage($languageEntities[array_rand($languageEntities)]);
            $manager->persist($movie);

            $serie = new Serie();
            $serie->setTitle("Serie $i");
            $serie->setPopularity(mt_rand(1, 100));
            $serie->setDescription("Description for Serie $i");
            $serie->addCategory($categoryEntities[array_rand($categoryEntities)]);
            $serie->addLanguage($languageEntities[array_rand($languageEntities)]);
            $manager->persist($serie);
        }

        // Add Playlists
        $playlist = new Playlist();
        $playlist->setName('My Favorite Playlist');
        $playlist->setUser($user);
        $manager->persist($playlist);

        $manager->flush();
    }
}
