<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\EventListener;

use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

/**
 * @final since sonata-project/translation-bundle 2.7
 *
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class LocaleSwitcherListener
{
    /**
     * @param string $eventName
     */
    public function onBlock(BlockEvent $event, $eventName): void
    {
        $settings = $event->getSettings();
        if ('sonata.block.event.sonata.admin.show.top' === $eventName) {
            $settings['locale_switcher_route'] = 'show';
        }
        if ('sonata.block.event.sonata.admin.list.table.top' === $eventName) {
            $settings['locale_switcher_route'] = 'list';
        }

        $block = new Block();
        $block->setSettings($settings);
        $block->setName('sonata_translation.block.locale_switcher');
        $block->setType('sonata_translation.block.locale_switcher');

        $event->addBlock($block);
    }
}
