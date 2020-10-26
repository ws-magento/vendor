<?php


namespace WS\Manufacturer\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Manufacturer extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('WS\Manufacturer\Model\ResourceModel\Manufacturer');
    }
}
