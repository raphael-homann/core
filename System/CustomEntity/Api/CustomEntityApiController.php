<?php declare(strict_types=1);

namespace Shopware\Core\System\CustomEntity\Api;

use Shopware\Core\Framework\Api\Controller\ApiController;
use Shopware\Core\Framework\Api\Response\ResponseFactoryInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\DefinitionNotFoundException;
use Shopware\Core\System\CustomEntity\Exception\CustomEntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(defaults={"_routeScope"={"api"}})
 */
class CustomEntityApiController extends ApiController
{
    /**
     * @Route(
     *     "/api/custom-entity-{entityName}/{path}",
     *     name="api.custom_entity_entity.detail",
     *     requirements={"path"="[0-9a-f]{32}(\/(extensions\/)?[a-zA-Z-]+\/[0-9a-f]{32})*\/?$"},
     *     methods={"GET"}
     * )
     */
    public function detail(Request $request, Context $context, ResponseFactoryInterface $responseFactory, string $entityName, string $path): Response
    {
        $entity = 'custom-entity-' . $entityName;

        try {
            return parent::detail($request, $context, $responseFactory, $entity, $path);
        } catch (DefinitionNotFoundException $e) {
            throw new CustomEntityNotFoundException($entityName);
        }
    }

    /**
     * @Route(
     *     "/api/search-ids/custom-entity-{entityName}{path}",
     *     name="api.custom_entity_entity.search-ids",
     *     requirements={"path"="(\/[0-9a-f]{32}\/(extensions\/)?[a-zA-Z-]+)*\/?$"},
     *     methods={"POST"}
     * )
     */
    public function searchIds(Request $request, Context $context, ResponseFactoryInterface $responseFactory, string $entityName, string $path): Response
    {
        $entity = 'custom-entity-' . $entityName;

        try {
            return parent::searchIds($request, $context, $responseFactory, $entity, $path);
        } catch (DefinitionNotFoundException $e) {
            throw new CustomEntityNotFoundException($entityName);
        }
    }

    /**
     * @Route(
     *     "/api/search/custom-entity-{entityName}{path}",
     *     name="api.custom_entity_entity.search",
     *     requirements={"path"="(\/[0-9a-f]{32}\/(extensions\/)?[a-zA-Z-]+)*\/?$"},
     *     methods={"POST"}
     * )
     */
    public function search(Request $request, Context $context, ResponseFactoryInterface $responseFactory, string $entityName, string $path): Response
    {
        $entity = 'custom-entity-' . $entityName;

        try {
            return parent::search($request, $context, $responseFactory, $entity, $path);
        } catch (DefinitionNotFoundException $e) {
            throw new CustomEntityNotFoundException($entityName);
        }
    }

    /**
     * @Route(
     *     "/api/custom-entity-{entityName}{path}",
     *     name="api.custom_entity_entity.list",
     *     requirements={"path"="(\/[0-9a-f]{32}\/(extensions\/)?[a-zA-Z-]+)*\/?$"},
     *     methods={"GET"}
     * )
     */
    public function list(Request $request, Context $context, ResponseFactoryInterface $responseFactory, string $entityName, string $path): Response
    {
        $entity = 'custom-entity-' . $entityName;

        try {
            return parent::list($request, $context, $responseFactory, $entity, $path);
        } catch (DefinitionNotFoundException $e) {
            throw new CustomEntityNotFoundException($entityName);
        }
    }

    /**
     * @Route(
     *      "/api/custom-entity-{entityName}{path}",
     *     name="api.custom_entity_entity.create",
     *     requirements={"path"="(\/[0-9a-f]{32}\/(extensions\/)?[a-zA-Z-]+)*\/?$"},
     *     methods={"POST"}
     * )
     */
    public function create(Request $request, Context $context, ResponseFactoryInterface $responseFactory, string $entityName, string $path): Response
    {
        $entity = 'custom-entity-' . $entityName;

        try {
            return parent::create($request, $context, $responseFactory, $entity, $path);
        } catch (DefinitionNotFoundException $e) {
            throw new CustomEntityNotFoundException($entityName);
        }
    }

    /**
     * @Route(
     *     "/api/custom-entity-{entityName}/{path}",
     *     name="api.custom_entity_entity.update",
     *     requirements={"path"="[0-9a-f]{32}(\/(extensions\/)?[a-zA-Z-]+\/[0-9a-f]{32})*\/?$"},
     *     methods={"PATCH"}
     * )
     */
    public function update(Request $request, Context $context, ResponseFactoryInterface $responseFactory, string $entityName, string $path): Response
    {
        $entity = 'custom-entity-' . $entityName;

        try {
            return parent::update($request, $context, $responseFactory, $entity, $path);
        } catch (DefinitionNotFoundException $e) {
            throw new CustomEntityNotFoundException($entityName);
        }
    }

    /**
     * @Route(
     *     "/api/custom-entity-{entityName}/{path}",
     *     name="api.custom_entity_entity.delete",
     *     requirements={"path"="[0-9a-f]{32}(\/(extensions\/)?[a-zA-Z-]+\/[0-9a-f]{32})*\/?$"},
     *     methods={"DELETE"}
     * )
     */
    public function delete(Request $request, Context $context, ResponseFactoryInterface $responseFactory, string $entityName, string $path): Response
    {
        $entity = 'custom-entity-' . $entityName;

        try {
            return parent::delete($request, $context, $responseFactory, $entity, $path);
        } catch (DefinitionNotFoundException $e) {
            throw new CustomEntityNotFoundException($entityName);
        }
    }
}
