<?php


namespace WS\Manufacturer\Api;


use WS\Manufacturer\Api\Data\ManufacturerInterface;

interface GetManufacturerByIdentifierInterface
{
    /**
     * Load block data by given block identifier.
     *
     * @param string $identifier
     * @param int $storeId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @return ManufacturerInterface
     * @since 103.0.0
     */
    public function execute(string $identifier, int $storeId);
}
