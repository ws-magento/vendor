<?php

namespace WS\Manufacturer\Block\Catalog\Category\View;

use Magento\Catalog\Block\Product\Context;
use WS\Manufacturer\Api\Data\ManufacturerInterface;
use WS\Manufacturer\Api\ManufacturerRepositoryInterface;

class Manufacturer extends \Magento\Catalog\Block\Product\ProductList\Item\Block
{
    /**
     * @var ManufacturerRepositoryInterface
     */
    private $manufacturerRepository;

    public function __construct(
        Context $context,
        ManufacturerRepositoryInterface $manufacturerRepository,
        array $data = []
    ) {
        $this->manufacturerRepository = $manufacturerRepository;
        parent::__construct($context, $data);
    }

    /**
     * @return ManufacturerInterface[] | false
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getManufacturers()
    {
        $product = $this->getProduct();
        $manIds = $product->getData('manufacturer_id');
        if (!$manIds) {
            return false;
        }

        if (!$manufacturers = $this->manufacturerRepository->getByIds($manIds)) {
            return false;
        }

        return $manufacturers;
    }
}
