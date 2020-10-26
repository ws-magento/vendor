<?php

namespace WS\Manufacturer\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Manufacturer extends AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    ) {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('manufacturer', 'manufacturer_id');
    }
}
