<?php declare(strict_types=1);

namespace Shopware\Core\Framework\MessageQueue;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class MessageQueueSizeCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return MessageQueueSizeEntity::class;
    }
}
