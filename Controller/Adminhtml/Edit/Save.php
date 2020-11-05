<?php

namespace WS\Manufacturer\Controller\Adminhtml\Edit;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use WS\Manufacturer\Api\ManufacturerRepositoryInterface;
use WS\Manufacturer\Model\Manufacturer\DataProvider;
use WS\Manufacturer\Model\ManufacturerFactory;

class Save extends \WS\Manufacturer\Controller\Adminhtml\Edit\Manufacturer
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
        DataPersistorInterface $dataPersistor,
        ManufacturerFactory $manFactory = null,
        ManufacturerRepositoryInterface $manRepository = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->manFactory = $manFactory ?: ObjectManager::getInstance()->create(ManufacturerFactory::class);
        $this->manRepository = $manRepository ?: ObjectManager::getInstance()->create(ManufacturerRepositoryInterface::class);
        parent::__construct($context, $coreRegistry);
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if (empty($data['manufacturer_id'])) {
                $data['manufacturer_id'] = null;
            }

            if ($data['image'] && is_array($data['image'])){
                $link = $data['image'][0]['url'];
                if (strpos($link, 'http') === 0){
                    $link = parse_url($link, PHP_URL_PATH);
                }
                $data['image'] = $link;
            }

            /** @var \WS\Manufacturer\Model\Manufacturer $model */
            $model = $this->manFactory->create();

            $id = $this->getRequest()->getParam('id');
            if ($id) {
                try {
                    $model = $this->manRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('Manufacturer not found!'));
                    return $resultRedirect->setPath('*/all/index');
                }
            }

            try {
                $data = $this->_filterFoodData($data);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $this->manRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the manufacturer.'));
                $this->dataPersistor->clear(DataProvider::TEMP_NAME);
                return $this->processBlockReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the manufacturer.'));
            }

            $this->dataPersistor->set(DataProvider::TEMP_NAME, $data);
            return $resultRedirect->setPath('*/all/update', ['id' => $id]);
        }
        return $resultRedirect->setPath('*/all/index');
    }

    private function processBlockReturn(\WS\Manufacturer\Model\Manufacturer $model, $data, \Magento\Backend\Model\View\Result\Redirect $resultRedirect)
    {
        $redirect = $data['back'] ? $data['back'] : 'close';

        if ($redirect ==='continue') {
            $resultRedirect->setPath('*/all/update', ['id' => $model->getId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/all/index');
        } elseif ($redirect === 'duplicate') {
            $duplicateModel = $this->manFactory->create(['data' => $data]);
            $duplicateModel->setId(null);
            $this->manRepository->save($duplicateModel);
            $id = $duplicateModel->getId();
            $this->messageManager->addSuccessMessage(__('You duplicated the manufacturer record.'));
            $this->dataPersistor->set(DataProvider::TEMP_NAME, $data);
            $resultRedirect->setPath('*/all/update', ['id' => $id]);
        }
        return $resultRedirect;
    }

    /**$exception->getMessage()
     * @param array $rawData
     * @return array
     */
    public function _filterFoodData(array $rawData)
    {
        $data = $rawData;

        if (isset($data['image'])) {
            if ($data['image'] && is_array($data['image'])) {
                $data['image'] = $data['image'][0]['url'];
            }
        }else{
            $data['image'] = null;
        }
//        $images_str = '';
//        if (isset($data['images'])) {
//            foreach ($data['images'] as $key => $image) {
//                if (isset($image['name'])) {
//                    $images_str = $images_str . $image['name'] . ';';
//                }
//                if (isset($image['images'])) {
//                    $images_str = $images_str . $image['images'] . ";";
//                }
//            }
//        }
//        if (!empty($images_str)) {
//            $images_str = mb_substr($images_str, 0, -1);
//            $data['images'] = $images_str;
//        } else {
//            $data['images'] = null;
//        }
        return $data;
    }

}
