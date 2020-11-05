<?php

namespace WS\Manufacturer\Block\Adminhtml\Edit;

use Magento\Backend\Block\Widget\Context;
use WS\Manufacturer\Api\ManufacturerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GenericButton
 */
class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var ManufacturerRepositoryInterface
     */
    protected $manufacturerRepository;

    /**
     * @param Context $context
     * @param ManufacturerRepositoryInterface $manufacturerRepository
     */
    public function __construct(
        Context $context,
        ManufacturerRepositoryInterface $manufacturerRepository
    ) {
        $this->context = $context;
        $this->manufacturerRepository = $manufacturerRepository;
    }

    /**
     * Return CMS block ID
     *
     * @return int|null
     */
    public function getManufacturerId()
    {
        try {
            return $this->manufacturerRepository->getById(
                $this->context->getRequest()->getParam('id')
            )->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
