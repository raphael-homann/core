<?php declare(strict_types=1);

namespace Shopware\Core\Content\Test\Product\ProductFeatureSet;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use function Flag\skipTestNext6997;

class ProductFeatureSetPropertyTest extends TestCase
{
    use IntegrationTestBehaviour;
    use ProductFeatureSetFixtures;

    protected function setUp(): void
    {
        skipTestNext6997($this);
    }

    /**
     * @testWith    ["featureSets"]
     */
    public function testDefinitionFieldsAreComplete(string $field): void
    {
        $definition = $this->getContainer()->get(ProductDefinition::class);

        static::assertTrue($definition->getFields()->has($field));
    }

    /**
     * @testWith    ["getFeatureSets"]
     */
    public function testEntityIsComplete(string $method): void
    {
        static::assertTrue(method_exists(ProductEntity::class, $method));
    }

    /**
     * @testWith    ["FeatureSetBasic"]
     *              ["FeatureSetComplete"]
     */
    public function testFeatureSetsCanBeCreated(string $type): void
    {
        $this->getFeatureSetFixture($type);
    }
}
