<?php

declare(strict_types=1);

namespace Nextstore\SyliusLiveModulePlugin\Model;

use Doctrine\Common\Collections\Collection;

trait ProductTrait
{
    /**
     * @ORM\ManyToMany(targetEntity="Nextstore\SyliusLiveModulePlugin\Model\Live", mappedBy="products")
     */
    private $lives;

    /**
     * @return Collection|LiveInterface[]
     */
    public function getLives(): Collection
    {
        return $this->lives;
    }

    public function addLive(Live $live): void
    {
        if (!$this->lives->contains($live)) {
            $this->lives->add($live);
        }
    }
}
