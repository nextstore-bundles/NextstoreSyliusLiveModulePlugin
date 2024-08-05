<?php

declare(strict_types=1);

namespace Nextstore\SyliusLiveModulePlugin\Uploader;

use Gaufrette\Filesystem;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class LiveImageUploader
{
    public function __construct(
        private Filesystem $filesystem,
        private CacheManager $imagineCacheManager,
        private DataManager $imagineDataManager,
        private FilterManager $imagineFilterManager,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function upload(UploadedFile $file): string
    {
        $pathPrefix = 'live-image';
        $fileContents = file_get_contents($file->getPathname());

        do {
            $hash = bin2hex(random_bytes(16));
            $path = $this->expandPath($hash . '.' . $file->guessExtension(), $pathPrefix, null);
        } while ($this->filesystem->has($path));

        try {
            $this->filesystem->write(
                $path,
                $fileContents,
            );
        } catch (FileException $e) {
            return '';
        }

        return $path;
    }

    private function expandPath(
        string $path,
        string $pathPrefix,
        ?string $originalName = null,
    ): string {
        return sprintf(
            '%s/%s/%s/%s',
            $pathPrefix,
            substr($path, 0, 2),
            substr($path, 2, 2),
            $originalName ?? substr($path, 4),
        );
    }
}
