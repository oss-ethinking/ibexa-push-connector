<?php

namespace Ethinking\PushConnector\Connector\Channels\Mapper\Content\Fields;

use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\ImageAsset\AssetMapper;
use Ethinking\PushConnector\Connector\Channels\Mapper\Content\Fields\Helper\ImageAssetMapperTrait;

/**
 * Class ImageAssetFieldMapping
 * @package Ethinking\PushConnector\Connector\Channels\Mapper\Content\Fields
 */
class ImageAssetFieldMapping extends AbstractContentFieldsMapping
{
    /**
     * @param $identifier
     * @return mixed|void
     */
    public function support($identifier)
    {
        // TODO: Implement support() method.
    }

    /**
     * @param \eZ\Publish\API\Repository\Values\Content\Field $field
     * @return mixed|null
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function value(Field $field)
    {
        /** @var \eZ\Publish\Core\FieldType\ImageAsset\Value $fieldValue */
        $fieldValue = $field->value;

        $imageContentId = $fieldValue->destinationContentId;
        if (!$imageContentId) {
            return null;
        }
        /** @var \eZ\Publish\Core\FieldType\Image\Value $imageField */
        $imageField = $this->getContent($imageContentId)->getFieldValue($this->imageAssetMapper->getImageAssetContentFieldIdentifier());

        return $imageField->uri;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'ezimageasset';
    }
}
