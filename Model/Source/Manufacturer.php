<?php


namespace WS\Manufacturer\Model\Source;

class Manufacturer extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    /** @var \WS\Manufacturer\Model\Manufacturer */
    protected $model;

    public function __construct(
        \WS\Manufacturer\Model\Manufacturer $model
    )
    {
        $this->model = $model;
    }

    public function getAllOptions() {
        $items = $this->model->getCollection()->toArray();
        if ($this->_options === null) {
            $this->_options = [['label' => __('--Select--'), 'value' => '']];
            foreach ($items['items'] as $item){
                $this->_options[] = [
                    'label' => $item['name'],
                    'value' => $item['manufacturer_id']
                ];
            }
        }
        return $this->_options;
    }
}
