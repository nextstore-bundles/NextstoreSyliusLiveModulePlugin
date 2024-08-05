<?php

declare(strict_types=1);

namespace Nextstore\SyliusLiveModulePlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\HttpFoundation\File\File;

class Live implements LiveInterface
{
    public const SHOP_EMART = "emart";
    public const SHOP_COSTCO = "costco";

    protected ?int $id = null;

    private string $code;

    private string $name;

    private ?string $description;

    private ?string $fbLiveUrl;

    private ?string $fbLiveEmbedCode;

    private \DateTime $startDate;

    private bool $enabled = false;

    private ?string $shop;

    /**
     * @var Collection|ProductInterface[]
     */
    private $products;

    private ?ProductInterface $featuredProduct;

    private ?string $thumbnailPath;
    private ?File $thumbnailFile;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getShop(): ?string
    {
        return $this->shop;
    }

    public function setShop(?string $shop): void
    {
        $this->shop = $shop;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getFbLiveUrl(): ?string
    {
        return $this->fbLiveUrl;
    }

    public function setFbLiveUrl(?string $fbLiveUrl): void
    {
        $this->fbLiveUrl = $fbLiveUrl;
    }

    public function getFbLiveEmbedCode(): ?string
    {
        return $this->fbLiveEmbedCode;
    }

    public function setFbLiveEmbedCode(?string $fbLiveEmbedCode): void
    {
        $this->fbLiveEmbedCode = $fbLiveEmbedCode;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return Collection|ProductInterface[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(ProductInterface $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }
        return $this;
    }
    public function removeProduct(ProductInterface $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
        }
        return $this;
    }

    public function getFeaturedProduct(): ?ProductInterface
    {
        return $this->featuredProduct;
    }

    public function setFeaturedProduct(?ProductInterface $featuredProduct): void
    {
        $this->featuredProduct = $featuredProduct;
    }

    public function setThumbnailFile(?File $file): void
    {
        $this->thumbnailFile = $file;
    }

    public function getThumbnailFile(): ?File
    {
        return $this->thumbnailFile;
    }

    public function setThumbnailPath(?string $path): void
    {
        $this->thumbnailPath = $path;
    }

    public function getThumbnailPath(): ?string
    {
        return $this->thumbnailPath;
    }
}
