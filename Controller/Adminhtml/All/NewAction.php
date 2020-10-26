<?php


namespace WS\Manufacturer\Controller\Adminhtml\All;


use Magento\Framework\App\ResponseInterface;

class NewAction extends Items
{
    public function execute()
    {
        $this->_forward('update');
    }
}
