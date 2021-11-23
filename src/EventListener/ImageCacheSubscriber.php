<?php

namespace App\EventListener;

use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;
use Vich\UploaderBundle\Storage\StorageInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ImageCacheSubscriber implements EventSubscriberInterface
{
    private $storage;
    private $cacheManager;

    public function __construct(StorageInterface $storage, CacheManager $cacheManager)
    {
        $this->storage = $storage;
        $this->cacheManager = $cacheManager;
    }

    public function onRemove(Event $event)
    {
        $path = $this->storage->resolveUri($event->getObject(), $event->getMapping()->getFilePropertyName());
        $this->cacheManager->remove($path);
    }

    public static function getSubscribedEvents()
    {
        return [Events::PRE_REMOVE => 'onRemove'];
    }
}
