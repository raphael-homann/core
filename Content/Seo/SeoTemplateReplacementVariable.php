<?php declare(strict_types=1);

namespace Shopware\Core\Content\Seo;

/**
 * @package sales-channel
 */
class SeoTemplateReplacementVariable
{
    public function __construct(private readonly string $mappedEntityName, private readonly ?string $mappedEntityFields = null)
    {
    }

    public function hasMappedFields(): bool
    {
        return $this->mappedEntityFields !== null;
    }

    public function getMappedEntityName(): string
    {
        return $this->mappedEntityName;
    }

    public function getMappedEntityFields(): ?string
    {
        return $this->mappedEntityFields;
    }
}
