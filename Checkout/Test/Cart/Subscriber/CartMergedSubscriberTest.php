<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Test\Cart\Subscriber;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Cart\Event\CartMergedEvent;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Storefront\Event\CartMergedSubscriber;

class CartMergedSubscriberTest extends TestCase
{
    use IntegrationTestBehaviour;

    public function testMergedHintIsAdded(): void
    {
        $this->getContainer()->get(CartMergedSubscriber::class)->addCartMergedNoticeFlash($this->createMock(CartMergedEvent::class));

        $flashBag = $this->getContainer()->get('session')->getFlashBag();

        static::assertNotEmpty($infoFlash = $flashBag->get('info'));

        static::assertEquals('checkout.cart-merged-hint', $infoFlash[0]);
    }
}
