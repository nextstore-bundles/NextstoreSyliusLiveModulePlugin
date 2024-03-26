<?php

declare(strict_types=1);

namespace Nextstore\SyliusLiveModulePlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $sales = $menu->getChild('sales');

        $sales
            ->addChild('live', ['route' => 'nextstore_sylius_live_module_admin_live_index'])
            ->setLabel('nextstore_sylius_live_module.ui.live')
            ->setLabelAttribute('icon', 'video')
        ;
    }
}
