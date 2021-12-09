<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutListener
{
    private $entityManager;
    private $tokenStorage;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function onSymfonyComponentSecurityHttpEventLogoutEvent(LogoutEvent $logoutEvent)
    {       
        if ($this->tokenStorage->getToken()) {
            $user = $this->tokenStorage->getToken()->getUser();

            if ( ($user instanceof User) && ($user->getIsActive(true)) ) {
                $user->setIsActive(false);
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $logoutEvent->getResponse();
            }
        }
    }
}