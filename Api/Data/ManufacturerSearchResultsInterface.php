<?php

namespace WS\Manufacturer\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;
use WS\Manufacturer\Api\Data\ManufacturerInterface;

interface ManufacturerSearchResultsInterface extends SearchResultsInterface
{
    /**
     *  Return manufacturer list
     *
     * @return ManufacturerInterface[]
     */
    public function getItems();

    /**
     * @param $items ManufacturerInterface[]
     * @return ManufacturerSearchResultsInterface
     */
    public function setItems(array $items);
}
