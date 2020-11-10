<?php

namespace WS\Manufacturer\Block\Catalog\Product\View;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product;
use WS\Manufacturer\Api\ManufacturerRepositoryInterface;

class Manufacturer extends \Magento\Catalog\Block\Product\View
{
    /**
     * @var ManufacturerRepositoryInterface
     */
    private $manufacturerRepository;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    private $_product;

    public function __construct(
        Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        ManufacturerRepositoryInterface $manufacturerRepository,
        array $data = []
    ) {
        $this->manufacturerRepository = $manufacturerRepository;
        parent::__construct($context, $urlEncoder, $jsonEncoder, $string, $productHelper, $productTypeConfig, $localeFormat, $customerSession, $productRepository, $priceCurrency, $data);
    }

    /**
     * @return false|\WS\Manufacturer\Api\Data\ManufacturerInterface
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

    public function getManufacturersFiltered()
    {
        $out = [];
        if ($items = $this->getManufacturers()) {
            foreach ($items as $item) {
                if ($src = $item->getImage()) {
                    $out[] = $item;
                }
            }
        }
        return $out;
    }
}
