<?php

declare(strict_types=1);

namespace Nextstore\SyliusLiveModulePlugin\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Nextstore\SyliusLiveModulePlugin\Model\Live;
use Nextstore\SyliusLiveModulePlugin\Uploader\LiveImageUploader;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Webmozart\Assert\Assert;

final class LiveImageUploadListener
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LiveImageUploader $uploader
    ) {
    }

    public function uploadImages(ResourceControllerEvent $event): void
    {
        /** @var Live $live */
        $live = $event->getSubject();

        Assert::isInstanceOf($live, Live::class);

        $imageFile = $live->getThumbnailFile();
        if ($imageFile !== null) {
            $path = $this->uploader->upload($imageFile);
            $live->setThumbnailPath($path);
            $live->setThumbnailFile(null);
            $this->entityManager->persist($live);
        }
    }
}
