<?php

namespace WS\Manufacturer\Model\Manufacturer;

use Magento\Catalog\Model\Category\FileInfo;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use WS\Manufacturer\Model\Manufacturer;
use WS\Manufacturer\Model\ResourceModel\Manufacturer\Collection;
use WS\Manufacturer\Model\ResourceModel\Manufacturer\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{
    const TEMP_NAME = 'current_man';

    /**
     * @var Collection
     */
    public $collection;

    /**
     * @var DataPersistorInterface
     */
    public $dataPersistor;

    /**
     * @var Manufacturer[]
     */
    public $loadedData;
    /**
     * @var FileInfo|null
     */
    private $fileInfo;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $manCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $manCollectionFactory,
        DataPersistorInterface $dataPersistor,
        FileInfo $fileInfo = null,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->fileInfo = $fileInfo ?: ObjectManager::getInstance()->get(FileInfo::class);
        $this->collection = $manCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $man) {
            $tempData = $man->getData();
            if ($tempData['image']) {
                $fileName = $tempData['image'];
                if ($this->fileInfo->isExist($fileName)) {
                    $name = explode('/', $fileName);
                    $name = end($name);

                    $stat = $this->fileInfo->getStat($fileName);
                    $mime = $this->fileInfo->getMimeType($fileName);

                    $tempData['image'] = [[
                        'name' => $name,
                        'url' => $man->getImageUrl(),
                        'size' => isset($stat) ? $stat['size'] : 0,
                        'type' => $mime
                    ]];
                }else{
                    $tempData['image'] = null;
                }
            }
            $this->loadedData[$man->getId()] = $tempData;
        }

        return $this->loadedData;
    }
}
