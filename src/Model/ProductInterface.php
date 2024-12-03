<?php

declare(strict_types=1);

namespace Nextstore\SyliusLiveModulePlugin\Model;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ProductInterface as BaseProductInterface;

interface ProductInterface extends BaseProductInterface
{
    public function getLives(): Collection;
    public function addLive(?LiveInterface $live): void;
    public function removeLive(?LiveInterface $live): void;
}
