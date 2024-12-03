<?php

declare(strict_types=1);

namespace Nextstore\SyliusLiveModulePlugin\Model;

use Doctrine\Common\Collections\Collection;

trait ProductTrait
{
    /**
     * @var LiveInterface[]|Collection
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

    public function addLive(?LiveInterface $live): void
    {
        if (!$this->lives->contains($live)) {
            $this->lives->add($live);
        }
    }

    public function removeLive(?LiveInterface $live): void
    {
        if ($this->lives->contains($live)) {
            $this->lives->removeElement($live);
        }
    }
}
