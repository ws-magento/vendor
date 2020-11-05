<?php

namespace WS\Manufacturer\Api\Data;

/**
 * Interface ManufacturerInterface
 * @package WS\Manufacturer\Api\Data
 *
 *
 */
interface ManufacturerInterface
{
    /**
     * @return integer
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();
}
