<?php

namespace App\Controller\Other;

use App\Repository\SubscriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SubscriptionController extends AbstractController
{
    #[IsGranted(attribute: "ROLE_USER")]
    #[Route('/subscription', name: 'page_subscription_list')]
    public function list(SubscriptionRepository $subscriptionRepository, Security $security): Response
    {
        $subscriptions = $subscriptionRepository->findAll();
        $user = $security->getUser();
        dump($user);
        $userSubscription = $user?->getCurrentSubscription();

        return $this->render('other/abonnements.html.twig', [
            'subscriptions' => $subscriptions,
            'user_subscriptions' => $userSubscription,
        ]);
    }
}