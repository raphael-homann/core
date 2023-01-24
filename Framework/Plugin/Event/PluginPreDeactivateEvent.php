<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Plugin\Event;

use Shopware\Core\Framework\Plugin\Context\DeactivateContext;
use Shopware\Core\Framework\Plugin\PluginEntity;

/**
 * @package core
 */
class PluginPreDeactivateEvent extends PluginLifecycleEvent
{
    public function __construct(PluginEntity $plugin, private readonly DeactivateContext $context)
    {
        parent::__construct($plugin);
    }

    public function getContext(): DeactivateContext
    {
        return $this->context;
    }
}
