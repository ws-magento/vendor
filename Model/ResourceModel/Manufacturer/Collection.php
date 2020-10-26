<?php

namespace WS\Manufacturer\Model\ResourceModel\Manufacturer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'manufacturer_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('WS\Manufacturer\Model\Manufacturer', 'WS\Manufacturer\Model\ResourceModel\Manufacturer');
    }
}
