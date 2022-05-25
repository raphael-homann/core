<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Test\App\Lifecycle\Persister;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\App\Lifecycle\Persister\ScriptPersister;
use Shopware\Core\Framework\Test\Store\ExtensionBehaviour;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;

class ScriptPersisterTest extends TestCase
{
    use ExtensionBehaviour;
    use IntegrationTestBehaviour;

    public function testRefreshDoesNotDeleteExistingScripts(): void
    {
        $this->installApp(__DIR__ . '/../../Manifest/_fixtures/test');

        static::assertSame(6, $this->fetchAppScriptCount());

        $scriptPersister = $this->getContainer()->get(ScriptPersister::class);

        $scriptPersister->refresh();

        static::assertSame(6, $this->fetchAppScriptCount());
    }

    private function fetchAppScriptCount(): int
    {
        return (int) $this->getContainer()->get(Connection::class)->fetchOne(
            'SELECT COUNT(id) FROM script'
        );
    }
}
