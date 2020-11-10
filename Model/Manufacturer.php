<?php

namespace WS\Manufacturer\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use WS\Manufacturer\Api\Data\ManufacturerInterface;

//use WS\Manufacturer\Model\ResourceModel\Manufacturer\Collection;

/**
 * Class Manufacturer
 * @package WS\Manufacturer\Model *
 *
 * @method Manufacturer setStoreId(int $storeId)
 * @method int getStoreId()
 */
class Manufacturer extends AbstractModel implements ManufacturerInterface, IdentityInterface
{
    const CACHE_TAG = 'ws_man';

    private $_loadedData;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $_storeManager;

    protected function _construct()
    {
        $this->_init('WS\Manufacturer\Model\ResourceModel\Manufacturer');
    }

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function getName()
    {
        return $this->getData(self::NAME);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getImageUrl()
    {
        $url = false;
        $image = $this->getData(self::IMG);
        if ($image) {
            if (is_string($image)) {
                $store = $this->_storeManager->getStore();

                $isRelativeUrl = substr($image, 0, 1) === '/';

                $mediaBaseUrl = $store->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                );

                if ($isRelativeUrl) {
                    $url = $image;
                } else {
                    $url = $mediaBaseUrl
                        . 'manufacturer/images'
                        . '/'
                        . $image;
                }
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }

    public function getImage()
    {
        return $this->getData(self::IMG);
    }

    public function getDateAdded()
    {
        return $this->getData(self::DATE_ADD);
    }

    public function getDescription()
    {
        return $this->getData(self::DESC);
    }

    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    public function setImage($image)
    {
        return $this->setData(self::IMG, $image);
    }

    public function setDescription($description)
    {
        return $this->setData(self::DESC, $description);
    }

    public function setDateAdded($date)
    {
        return $this->setData(self::DATE_ADD, $date);
    }
}
