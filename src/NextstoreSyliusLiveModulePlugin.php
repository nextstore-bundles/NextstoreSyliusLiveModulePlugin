<?php

declare(strict_types=1);

namespace Nextstore\SyliusLiveModulePlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;

final class NextstoreSyliusLiveModulePlugin extends AbstractResourceBundle
{
    use SyliusPluginTrait;
    /**
     * {@inheritdoc}
     */
    public function getSupportedDrivers(): array
    {
        return [SyliusResourceBundle::DRIVER_DOCTRINE_ORM];
    }
}
