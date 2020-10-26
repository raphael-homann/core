<?php declare(strict_types=1);

namespace Shopware\Core\Content\Sitemap\SalesChannel;

use OpenApi\Annotations as OA;
use Shopware\Core\Content\Sitemap\Exception\AlreadyLockedException;
use Shopware\Core\Content\Sitemap\Service\SitemapExporterInterface;
use Shopware\Core\Content\Sitemap\Service\SitemapListerInterface;
use Shopware\Core\Content\Sitemap\Struct\SitemapCollection;
use Shopware\Core\Framework\Plugin\Exception\DecorationPatternException;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"store-api"})
 */
class SitemapRoute extends AbstractSitemapRoute
{
    /**
     * @var SitemapListerInterface
     */
    private $sitemapLister;

    /**
     * @var SystemConfigService
     */
    private $systemConfigService;

    /**
     * @var SitemapExporterInterface
     */
    private $sitemapExporter;

    public function __construct(SitemapListerInterface $sitemapLister, SystemConfigService $systemConfigService, SitemapExporterInterface $sitemapExporter)
    {
        $this->sitemapLister = $sitemapLister;
        $this->systemConfigService = $systemConfigService;
        $this->sitemapExporter = $sitemapExporter;
    }

    /**
     * @OA\Get(
     *      path="/sitemap",
     *      summary="Sitemap",
     *      operationId="readSitemap",
     *      tags={"Store API", "Sitemap"},
     *      @OA\Response(
     *          response="200",
     *          description="",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/definitions/Sitemap"))
     *     )
     * )
     * @Route(path="/store-api/v{version}/sitemap", name="store-api.sitemap", methods={"GET", "POST"})
     */
    public function load(Request $request, SalesChannelContext $context): SitemapRouteResponse
    {
        $sitemaps = $this->sitemapLister->getSitemaps($context);

        if ($this->systemConfigService->getInt('core.sitemap.sitemapRefreshStrategy') !== SitemapExporterInterface::STRATEGY_LIVE) {
            return new SitemapRouteResponse(new SitemapCollection($sitemaps));
        }

        // Close session to prevent session locking from waiting in case there is another request coming in
        if ($request->hasSession()) {
            $request->getSession()->save();
        }

        try {
            $this->generateSitemap($context, true);
        } catch (AlreadyLockedException $exception) {
            // Silent catch, lock couldn't be acquired. Some other process already generates the sitemap.
        }

        $sitemaps = $this->sitemapLister->getSitemaps($context);

        return new SitemapRouteResponse(new SitemapCollection($sitemaps));
    }

    public function getDecorated(): AbstractSitemapRoute
    {
        throw new DecorationPatternException(self::class);
    }

    private function generateSitemap(SalesChannelContext $salesChannelContext, bool $force, ?string $lastProvider = null, ?int $offset = null): void
    {
        $result = $this->sitemapExporter->generate($salesChannelContext, $force, $lastProvider, $offset);
        if ($result->isFinish() === false) {
            $this->generateSitemap($salesChannelContext, $force, $result->getProvider(), $result->getOffset());
        }
    }
}
