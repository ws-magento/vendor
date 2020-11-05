<?php

namespace WS\Manufacturer\Controller\Adminhtml\All;

class Update extends Items
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            if (!$manufacturer = $this->manRepository->getById($id)) {
                $this->messageManager->addError(__('The manufacturer does\'nt exists.'));
                return;
            }
        } else {
            $manufacturer = $this->manFactory->create();
        }

        $this->_coreRegistry->register('current_man', $manufacturer);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $title = $manufacturer->getId()
            ? __('The Manufacturer Updating')
            : __('New Manufacturer');
        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }
}
