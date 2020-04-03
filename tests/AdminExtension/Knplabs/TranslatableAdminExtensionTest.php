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

namespace Sonata\TranslationBundle\Tests\AdminExtension\Knplabs;

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\TranslationBundle\Admin\Extension\Knplabs\TranslatableAdminExtension;
use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\Model\TranslatableInterface;
use Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs\TranslatableEntity;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group  translatable-knplabs
 */
final class TranslatableAdminExtensionTest extends WebTestCase
{
    /**
     * @var AdminInterface
     */
    protected $admin;

    /**
     * @var TranslatableEntity
     */
    protected $object;

    /**
     * @var TranslatableAdminExtension
     */
    protected $extension;

    protected function setUp(): void
    {
        $translatableChecker = new TranslatableChecker();
        $translatableChecker->setSupportedInterfaces([
            TranslatableInterface::class,
        ]);
        $this->extension = new TranslatableAdminExtension($translatableChecker);

        $request = $this->prophesize(Request::class);
        $request->get('tl')->willReturn('es');

        $this->admin = $this->prophesize(AdminInterface::class);
        $this->admin->getRequest()->willReturn($request->reveal());
        $this->admin->hasRequest()->willReturn(true);

        $this->object = new TranslatableEntity();
    }

    public function testSetLocaleForTranslatableObject(): void
    {
        $this->extension->alterNewInstance($this->admin->reveal(), $this->object);

        $this->assertSame('es', $this->object->getLocale());
    }

    public function testAlterObjectForTranslatableObject(): void
    {
        $this->extension->alterObject($this->admin->reveal(), $this->object);

        $this->assertSame('es', $this->object->getLocale());
    }

    public function testPreUpdate(): void
    {
        $object = $this->prophesize(TranslatableEntity::class);
        $object->mergeNewTranslations()->shouldBeCalled();

        $this->extension->preUpdate($this->admin->reveal(), $object->reveal());
    }

    public function testPrePersist(): void
    {
        $object = $this->prophesize(TranslatableEntity::class);
        $object->mergeNewTranslations()->shouldBeCalled();

        $this->extension->prePersist($this->admin->reveal(), $object->reveal());
    }
}
