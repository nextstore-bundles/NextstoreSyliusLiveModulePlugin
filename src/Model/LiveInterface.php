<?php

declare(strict_types=1);

namespace Nextstore\SyliusLiveModulePlugin\Model;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface LiveInterface extends ResourceInterface
{
    public function getCode(): ?string;

    public function setCode(string $code): void;

    public function getName(): ?string;

    public function getDescription(): ?string;

    public function setDescription(string $description): void;

    public function getShop(): ?string;

    public function setShop(?string $shop): void;

    public function isEnabled(): bool;

    public function setEnabled(bool $enabled): void;

    public function getFbLiveUrl(): ?string;

    public function setFbLiveUrl(?string $fbLiveUrl): void;

    public function getFbLiveEmbedCode(): ?string;

    public function setFbLiveEmbedCode(?string $fbLiveEmbedCode): void;

    public function getStartDate(): \DateTime;

    public function setStartDate(\DateTime $startDate): void;

    /**
     * @return Collection|ProductInterface[]
     */
    public function getProducts(): Collection;

    public function addProduct(ProductInterface $product): self;

    public function removeProduct(ProductInterface $product): self;

    public function getFeaturedProduct(): ?ProductInterface;

    public function setFeaturedProduct(
        ?ProductInterface $featuredProduct
    ): void;
}
