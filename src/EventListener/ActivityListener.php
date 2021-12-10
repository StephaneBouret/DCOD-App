<?php

namespace App\EventListener;

use DateTime;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ActivityListener
{
    private $entityManager;
    private $tokenStorage;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function onCoreController(ControllerEvent $event)
    {
        // Check that the current request is a "MAIN_REQUEST"
        // Ignore any sub-request
        if ($event->getRequestType() !== HttpKernel::MAIN_REQUEST) {
            return;
        }

        // Check token authentication availability
        if ($this->tokenStorage->getToken()) {
            $user = $this->tokenStorage->getToken()->getUser();

            if ( ($user instanceof User) && !($user->isActiveNow()) ) {
                $user->setLastActivityAt(new \DateTime());
                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }
        }
    }
}