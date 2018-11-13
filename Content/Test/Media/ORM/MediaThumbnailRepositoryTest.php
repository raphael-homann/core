<?php declare(strict_types=1);

namespace src\Core\Content\Test\Media\DataAbstractionLayer;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Media\DataAbstractionLayer\MediaThumbnailRepository;
use Shopware\Core\Content\Media\MediaProtectionFlags;
use Shopware\Core\Content\Media\Pathname\UrlGeneratorInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Read\ReadCriteria;
use Shopware\Core\Framework\DataAbstractionLayer\RepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Struct\Uuid;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;

class MediaThumbnailRepositoryTest extends TestCase
{
    use IntegrationTestBehaviour;

    private const FIXTURE_FILE = __DIR__ . '/../fixtures/shopware-logo.png';

    /**
     * @var RepositoryInterface
     */
    private $mediaRepository;

    /**
     * @var MediaThumbnailRepository
     */
    private $thumbnailRepository;

    /**
     * @var Context
     */
    private $context;

    public function setUp()
    {
        $this->mediaRepository = $this->getContainer()->get('media.repository');
        $this->thumbnailRepository = $this->getContainer()->get('media_thumbnail.repository');

        $this->context = Context::createDefaultContext();
    }

    public function testRemoveThumbnail()
    {
        $mediaId = Uuid::uuid4()->getHex();
        $this->createThumbnailWithMedia($mediaId);
        $thumbnailPath = $this->createThumbnailFile($mediaId);

        $thumbnailIds = $this->thumbnailRepository->searchIds(new Criteria(), $this->context);
        $this->thumbnailRepository->delete($thumbnailIds->getIds(), $this->context);

        static::assertFalse($this->getPublicFilesystem()->has($thumbnailPath));
    }

    public function testRemoveThumbnailFromMedia()
    {
        $mediaId = Uuid::uuid4()->getHex();
        $this->createThumbnailWithMedia($mediaId);
        $thumbnailPath = $this->createThumbnailFile($mediaId);

        $mediaEntities = $this->mediaRepository->search(new ReadCriteria([$mediaId]), $this->context)->getEntities();
        $this->thumbnailRepository->deleteCascadingFromMedia($mediaEntities->get($mediaId), $this->context);

        static::assertFalse($this->getPublicFilesystem()->has($thumbnailPath));
    }

    private function createThumbnailWithMedia($mediaId)
    {
        $this->context->getWriteProtection()->allow(
            MediaProtectionFlags::WRITE_META_INFO,
            MediaProtectionFlags::WRITE_THUMBNAILS
        );

        $this->mediaRepository->create([
            [
                'id' => $mediaId,
                'name' => 'test media',
                'fileExtension' => 'png',
                'mimeType' => 'image/png',
                'thumbnails' => [
                    [
                        'width' => 100,
                        'height' => 200,
                        'highDpi' => false,
                    ],
                ],
            ],
        ], $this->context);

        $this->context->getWriteProtection()->disallow(MediaProtectionFlags::WRITE_META_INFO);
        $this->context->getWriteProtection()->disallow(MediaProtectionFlags::WRITE_THUMBNAILS);
    }

    private function createThumbnailFile($mediaId)
    {
        $urlGenerator = $this->getContainer()->get(UrlGeneratorInterface::class);
        $thumbnailPath = $urlGenerator->getRelativeThumbnailUrl(
            $mediaId,
            'png',
            100,
            200,
            false
        );

        $this->getPublicFilesystem()->putStream($thumbnailPath, fopen(self::FIXTURE_FILE, 'r'));

        return $thumbnailPath;
    }
}
