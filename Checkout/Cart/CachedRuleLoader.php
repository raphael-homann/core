<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Cart;

use Psr\Log\LoggerInterface;
use Shopware\Core\Content\Rule\RuleCollection;
use Shopware\Core\Framework\Adapter\Cache\CacheCompressor;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Feature;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

class CachedRuleLoader extends AbstractRuleLoader
{
    public const CACHE_KEY = 'cart-rules';

    private AbstractRuleLoader $decorated;

    private TagAwareAdapterInterface $cache;

    private LoggerInterface $logger;

    public function __construct(AbstractRuleLoader $decorated, TagAwareAdapterInterface $cache, LoggerInterface $logger)
    {
        $this->decorated = $decorated;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function getDecorated(): AbstractRuleLoader
    {
        return $this->decorated;
    }

    public function load(Context $context): RuleCollection
    {
        if (!Feature::isActive('FEATURE_NEXT_10514')) {
            return $this->getDecorated()->load($context);
        }

        $item = $this->cache->getItem(self::CACHE_KEY);

        if ($item->isHit() && $item->get()) {
            $this->logger->info('cache-hit: ' . self::CACHE_KEY);

            return CacheCompressor::uncompress($item);
        }

        $this->logger->info('cache-miss: ' . self::CACHE_KEY);

        $rules = $this->getDecorated()->load($context);

        $item = CacheCompressor::compress($item, $rules);
        $this->cache->save($item);

        return $rules;
    }
}
