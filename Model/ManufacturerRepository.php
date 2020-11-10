<?php

namespace WS\Manufacturer\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use WS\Manufacturer\Api\Data\ManufacturerInterface;
use WS\Manufacturer\Api\Data\ManufacturerInterfaceFactory;
use WS\Manufacturer\Api\Data\ManufacturerSearchResultsInterfaceFactory;
use WS\Manufacturer\Api\ManufacturerRepositoryInterface;
use WS\Manufacturer\Model\ResourceModel\Manufacturer as RecourseModel;
use WS\Manufacturer\Model\ResourceModel\Manufacturer\CollectionFactory as ManufacturerCollectionFactory;

class ManufacturerRepository implements ManufacturerRepositoryInterface
{
    /**
     * @var RecourseModel
     */
    protected $resource;

    /**
     * @var ManufacturerFactory
     */
    protected $manufacturerFactory;

    /**
     * @var ManufacturerCollectionFactory
     */
    protected $manufacturerCollectionFactory;

    /**
     * @var ManufacturerSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var ManufacturerInterfaceFactory
     */
    protected $dataManufacturerFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @param RecourseModel $resource
     * @param ManufacturerFactory $manufacturerFactory
     * @param ManufacturerInterfaceFactory $dataManufacturerFactory
     * @param ManufacturerCollectionFactory $manufacturerCollectionFactory
     * @param ManufacturerSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
//     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        RecourseModel $resource,
        ManufacturerFactory $manufacturerFactory,
        \WS\Manufacturer\Api\Data\ManufacturerInterfaceFactory $dataManufacturerFactory,
        ManufacturerCollectionFactory $manufacturerCollectionFactory,
        \WS\Manufacturer\Api\Data\ManufacturerSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
//        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->resource = $resource;
        $this->manufacturerFactory = $manufacturerFactory;
        $this->manufacturerCollectionFactory = $manufacturerCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataManufacturerFactory = $dataManufacturerFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
//        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
    }

    /**
     * @param ManufacturerInterface $manufacturer
     * @return void|ManufacturerInterface
     */
    public function save(ManufacturerInterface $manufacturer)
    {
        try {
            $this->resource->save($manufacturer);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
    }

    public function getById($manufacturerId)
    {
        $man = $this->manufacturerFactory->create();
        $this->resource->load($man, $manufacturerId);
        if (!$man->getId()) {
            throw new NoSuchEntityException(__('The Manufacturer with the "%1" ID doesn\'t exist.', $manufacturerId));
        }
        return $man;
    }

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var $collection \WS\Manufacturer\Model\ResourceModel\Manufacturer\Collection */
        $collection = $this->manufacturerCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var ManufacturerSearchResultsInterfaceFactory $results */
        $results = $this->searchResultsFactory->create();
        $results->setSearchCriteria($searchCriteria);
        $results->setItems($collection->getItems());
        $results->setTotalCount($collection->getSize());
        return $results;
    }

    public function delete(ManufacturerInterface $manufacturer)
    {
        try {
            $this->resource->delete($manufacturer);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    public function deleteById($manufacturerId)
    {
        return $this->delete($this->getById($manufacturerId));
    }

    /**
     * Retrieve collection processor
     *
     * @deprecated 102.0.0
     * @return CollectionProcessorInterface
     */
    private function getCollectionProcessor()
    {
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'Magento\Cms\Model\Api\SearchCriteria\BlockCollectionProcessor'
            );
        }
        return $this->collectionProcessor;
    }

    /**
     * @param string $ids
     * @return array|ManufacturerInterface[]
     */
    public function getByIds($ids)
    {
        if (is_string($ids)) {
            $ids = array_map(function ($v) {
                return (int)$v;
            }, explode(',', $ids));
        }
        $manCollection = $this->manufacturerCollectionFactory->create();

        $result = [];
        $manCollection->addFieldToFilter('manufacturer_id', $ids);
        foreach ($manCollection->getItems() as $man) {
            $result[] = $man;
        }

        return $result;
    }

    /**
     * @return array|ManufacturerInterface[]
     */
    public function getAll()
    {
        $manCollection = $this->manufacturerCollectionFactory->create();
        $manCollection->addOrder('manufacturer_id');

        $result = [];
        foreach ($manCollection->getItems() as $man) {
            $result[] = $man;
        }

        return $result;
    }
}
