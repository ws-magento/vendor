<?php

namespace WS\Manufacturer\Controller\Adminhtml\All;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use WS\Manufacturer\Api\ManufacturerRepositoryInterface;
use WS\Manufacturer\Model\ResourceModel\Manufacturer\CollectionFactory;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var ManufacturerRepositoryInterface
     */
    private $manRepository;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        ManufacturerRepositoryInterface $manRepository
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->manRepository = $manRepository;
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        $collectionSize = 0;
        foreach ($collection as $record) {
            $id = $record->getId();
            try {
                $forDelete = $this->manRepository->getById($id);
                if ($this->manRepository->delete($forDelete)){
                    $collectionSize++;
                }
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Manufacturer with id=%1 doesn\'t exist', $id));
            }

//            $forDelete = $this->_objectManager->get('WS\Manufacturer\Model\Manufacturer')->load();
//            if ($forDelete->delete()){
//                $collectionSize++;
//            }
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $collectionSize));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
