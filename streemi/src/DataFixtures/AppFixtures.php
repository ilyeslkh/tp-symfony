<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Language;
use App\Entity\Media;
use App\Entity\Movie;
use App\Entity\Playlist;
use App\Entity\PlaylistMedia;
use App\Entity\PlaylistSubscription;
use App\Entity\Season;
use App\Entity\Serie;
use App\Entity\Subscription;
use App\Entity\SubscriptionHistory;
use App\Entity\User;
use App\Entity\WatchHistory;
use App\Enum\CommentStatusEnum;
use App\Enum\UserAccountStatusEnum;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public const MAX_USERS = 10;
    public const MAX_MEDIA = 100;
    public const MAX_SUBSCRIPTIONS = 6;
    public const MAX_SEASONS = 3;
    public const MAX_EPISODES = 10;

    public const PLAYLISTS_PER_USER = 3;
    public const MAX_MEDIA_PER_PLAYLIST = 3;
    public const MAX_LANGUAGE_PER_MEDIA = 3;
    public const MAX_CATEGORY_PER_MEDIA = 3;
    public const MAX_SUBSCRIPTIONS_HISTORY_PER_USER = 3;
    public const MAX_COMMENTS_PER_MEDIA = 10;
    public const MAX_PLAYLIST_SUBSCRIPTION_PER_USERS = 3;

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $categories = [];
        $categoryNames = ['Action', 'Aventure', 'Comedie', 'Drame', 'Fantastique', 'Horreur', 'Romance'];
        foreach ($categoryNames as $name) {
            $category = new Category();
            $category->setName(strtolower($name));
            $category->setLabel($name);
            $manager->persist($category);
            $categories[] = $category;
        }

        $languages = [];
        $languageNames = ['French', 'English', 'Spanish', 'German', 'Italian'];
        foreach ($languageNames as $name) {
            $language = new Language();
            $language->setName($name);
            $language->setCode(substr($name, 0, 2));
            $manager->persist($language);
            $languages[] = $language;
        }

        $users = [];
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('admin@example.com');
        $userAdmin->setStatus(UserAccountStatusEnum::ACTIVE);
        $hashedPassword = $this->passwordHasher->hashPassword($userAdmin, 'adminpassword');
        $userAdmin->setPassword($hashedPassword);
        $userAdmin->addRole('ROLE_ADMIN');
        $manager->persist($userAdmin);
        $users[] = $userAdmin;

        for ($i = 0; $i < self::MAX_USERS; $i++) {
            $user = new User();
            $user->setUsername($faker->userName);
            $user->setEmail($faker->email);
            $user->setStatus(UserAccountStatusEnum::ACTIVE);
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $users[] = $user;
        }

        $mediaList = [];
        for ($i = 0; $i < self::MAX_MEDIA; $i++) {
            $media = $i % 2 === 0 ? new Movie() : new Serie();
            $media->setTitle($faker->sentence);
            $media->setShortDescription($faker->paragraph);
            $media->setLongDescription($faker->text);
            $media->setReleaseDate($faker->dateTimeBetween('-1 year', 'now'));
            $media->setCoverImage($faker->imageUrl(300, 400, 'media', true));
            $media->setStaff($faker->words(5));
            $media->setCasting($faker->words(5));
            $media->setDuration($faker->numberBetween(70, 200));

            foreach ($faker->randomElements($categories, rand(1, self::MAX_CATEGORY_PER_MEDIA)) as $category) {
                $media->addCategory($category);
            }

            foreach ($faker->randomElements($languages, rand(1, self::MAX_LANGUAGE_PER_MEDIA)) as $language) {
                $media->addLanguage($language);
            }

            $manager->persist($media);
            $mediaList[] = $media;
        }

        foreach ($mediaList as $media) {
            if ($media instanceof Serie) {
                for ($j = 0; $j < self::MAX_SEASONS; $j++) {
                    $season = new Season();
                    $season->setSeasonNumber($j + 1);
                    $season->setSerie($media);
                    $manager->persist($season);

                    for ($k = 0; $k < self::MAX_EPISODES; $k++) {
                        $episode = new Episode();
                        $episode->setTitle($faker->sentence);
                        $episode->setDuration($faker->dateTimeBetween('00:20:00', '01:00:00'));
                        $episode->setReleaseDate($faker->dateTimeBetween('-1 year', 'now'));
                        $episode->setSeason($season);
                        $manager->persist($episode);
                    }
                }
            }
        }

        foreach ($users as $user) {
            for ($j = 0; $j < self::PLAYLISTS_PER_USER; $j++) {
                $playlist = new Playlist();
                $playlist->setName($faker->sentence);
                $playlist->setCreatedAt(new DateTimeImmutable($faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s')));
                $playlist->setUpdatedAt(new DateTimeImmutable($faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s')));
                $playlist->setUser($user);
                $manager->persist($playlist);


                foreach ($faker->randomElements($mediaList, rand(1, self::MAX_MEDIA_PER_PLAYLIST)) as $media) {
                    $playlistMedia = new PlaylistMedia();
                    $playlistMedia->setAddedAt(new DateTimeImmutable());
                    $playlistMedia->setPlaylist($playlist);
                    $playlistMedia->setMedia($media);
                    $manager->persist($playlistMedia);
                }
                $playlistSubscription = new PlaylistSubscription();
                $playlistSubscription->setSubscribedAt(new DateTimeImmutable());
                $playlistSubscription->setUser($user);
                $playlistSubscription->setPlaylist($playlist);
                $manager->persist($playlistSubscription);


            }
        }
        $manager->flush();

        $subscriptions = [];
        $array = [
            ['name' => 'Abonnement 1 mois - HD', 'duration' => 1],
            ['name' => 'Abonnement 3 mois - Ultra-HD', 'duration' => 3],
            ['name' => 'Abonnement 6 mois - 4K', 'duration' => 6],
            ['name' => 'Abonnement 1 an - 4K-Family', 'duration' => 12],
        ];
        for ($i = 0; $i < count($array); $i++) {
            $subscription = new Subscription();
            $subscription->setName($array[$i]['name']);
            $subscription->setPrice($faker->numberBetween(10, 100));
            $subscription->setDuration($array[$i]['duration']);
            $subscription->setDescription($faker->words(3, true));
            $subscription->setDetails($faker->text);
            $manager->persist($subscription);
            $subscriptions[] = $subscription;
        }

        foreach ($users as $user) {
            if ($faker->boolean || $user === $users[0]) {
                $user->setCurrentSubscription($faker->randomElement($subscriptions));
            }
            for ($j = 0; $j < self::MAX_SUBSCRIPTIONS_HISTORY_PER_USER; $j++) {
                $subscriptionHistory = new SubscriptionHistory();
                $subscriptionHistory->setStartDate($faker->dateTimeBetween('-1 year', 'now'));
                $subscriptionHistory->setEndDate($faker->dateTimeBetween('now', '+1 year'));
                $subscriptionHistory->setUser($user);
                $subscriptionHistory->setSubscription($faker->randomElement($subscriptions));
                $manager->persist($subscriptionHistory);
            }
        }

        foreach ($mediaList as $media) {
            for ($j = 0; $j < self::MAX_COMMENTS_PER_MEDIA; $j++) {
                $comment = new Comment();
                $comment->setContent($faker->paragraph);
                $comment->setStatusEnum(CommentStatusEnum::PUBLISH);
                $comment->setUser($faker->randomElement($users));
                $comment->setMedia($media);
                $manager->persist($comment);
            }
        }



        foreach ($users as $user) {
            foreach ($mediaList as $media) {
                $watchHistory = new WatchHistory();
                $watchHistory->setLastWatched($faker->dateTimeBetween('-1 year', 'now'));
                $watchHistory->setUser($user);
                $watchHistory->setMedia($media);
                $watchHistory->setNumberOfViews($faker->numberBetween(1, 100));
                $manager->persist($watchHistory);
            }
        }

        $manager->flush();
    }
}