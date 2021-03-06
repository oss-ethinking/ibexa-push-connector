<?php

declare(strict_types=1);

namespace Ethinking\PushConnector\Connector\Services;

use eZ\Publish\API\Repository\Values\Content\Content;
use Ethinking\PushConnector\Connector\Channels\Registry\ContentFieldsMapperRegistryInterface;

/**
 * Class ContentMapperService
 * @package Ethinking\PushConnector\Connector\Services
 */
class ContentMapperService
{
    /** @var \Ethinking\PushConnector\Connector\Services\ConfigurationDefinitionService */
    private $configurationDefinitionService;

    /** @var \Ethinking\PushConnector\Connector\Channels\Registry\ContentFieldsMapperRegistryInterface */
    private $contentFieldsMapperRegistry;

    /** @var \eZ\Publish\API\Repository\Values\Content\Content */
    private $content;

    /**
     * ContentMapperService constructor.
     * @param \Ethinking\PushConnector\Connector\Services\ConfigurationDefinitionService $configurationDefinitionService
     * @param \Ethinking\PushConnector\Connector\Channels\Registry\ContentFieldsMapperRegistryInterface $contentFieldsMapperRegistry
     */
    public function __construct(
        ConfigurationDefinitionService $configurationDefinitionService,
        ContentFieldsMapperRegistryInterface $contentFieldsMapperRegistry
    ) {
        $this->configurationDefinitionService = $configurationDefinitionService;
        $this->contentFieldsMapperRegistry = $contentFieldsMapperRegistry;
    }

    /**
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     * @return \ArrayObject|null
     */
    public function getFieldsMappingFieldValue(Content $content): ? \ArrayObject
    {
        $this->content = $content;
        $enabledMapper = $this->configurationDefinitionService->getSupportedFields($content);
        if (!$enabledMapper) {
            return null;
        }

        return $this->getFieldsConfigurationDefinition($enabledMapper);
    }

    /**
     * @param $enabledMapper
     * @return \ArrayObject
     */
    private function getFieldsConfigurationDefinition($enabledMapper): \ArrayObject
    {
        foreach ($enabledMapper as $channel => $fields) {
            $mapping = $this->getFieldsValue($fields['fields']);
            $enabledMapper[$channel] = $mapping;
        }
        return $enabledMapper;
    }

    /**
     * @param $fields
     * @return array
     * @throws \eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    private function getFieldsValue($fields): array
    {
        foreach ($fields as $mapperKey => $fieldDefIdentifier) {
            $field = $this->content->getField($fieldDefIdentifier);
            $fieldValue = $this->contentFieldsMapperRegistry->getMapper($field->fieldTypeIdentifier)->value($field);
            $fields[$mapperKey] = $fieldValue;
        }
        return $fields;
    }
}
