<?php

namespace WS\Manufacturer\Controller\Adminhtml\Edit;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Registry;
use WS\Manufacturer\Api\ManufacturerRepositoryInterface;
use WS\Manufacturer\Model\ManufacturerFactory;

class Delete extends \WS\Manufacturer\Controller\Adminhtml\Edit\Manufacturer
{
    /**
     * @var ManufacturerRepositoryInterface
     */
    public $manRepository;

    /**
     * @var ManufacturerFactory
     */
    public $manFactory;

    /**
     * @var DataPersistorInterface
     */
    public $dataPersistor;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        ManufacturerRepositoryInterface $manRepository = null
    ) {
        $this->manRepository = $manRepository ?: ObjectManager::getInstance()->create(ManufacturerRepositoryInterface::class);
        parent::__construct($context, $coreRegistry);
    }

    public function execute()
    {
        if (!$id = $this->getRequest()->getParam('id')) {
            $this->messageManager->addErrorMessage(__('Manufacturer not found!'));
            return;
        }

        if (!$man = $this->manRepository->getById($id)) {
            $this->messageManager->addErrorMessage(__('Manufacturer not found!'));
            return;
        }

        $name = $man->getName();
        if ($this->manRepository->delete($man)) {
            $this->messageManager->addSuccessMessage(__('Manufacturer "%1" successfully deleted', $name));
        } else {
            $this->messageManager->addErrorMessage(__('Deleting error!'));
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/all/index');
    }
}
