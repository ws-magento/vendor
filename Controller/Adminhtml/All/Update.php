<?php

namespace WS\Manufacturer\Controller\Adminhtml\All;

use Magento\Framework\App\ResponseInterface;

class Update extends Items
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        $model = $this->_objectManager->create('WS\Manufacturer\Model\Manufacturer');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('The manufacturer does\'nt exists.'));
                $this->_redirect('manufacturer/all/*');
                return;
            }
        }
        // set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $this->_coreRegistry->register('current_man', $model);
        $this->_initAction();
        if ($model->getId()) {
            $title = __('The Manufacturer Updating');
        } else {
            $title = __('New Manufacturer');
        }
        $this->_view->getPage()->getConfig()->getTitle()->prepend($title);
        $this->_view->getLayout()->getBlock('manufacturer_all_update');
        $this->_view->renderLayout();
    }
}
