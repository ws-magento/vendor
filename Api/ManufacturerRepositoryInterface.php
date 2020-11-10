<?php

namespace WS\Manufacturer\Api;

use Magento\Framework\Exception\LocalizedException;
use WS\Manufacturer\Api\Data\ManufacturerInterface;
use WS\Manufacturer\Api\Data\ManufacturerSearchResultsInterface;

interface ManufacturerRepositoryInterface
{
    /**
     * Save block.
     *
     * @param ManufacturerInterface $manufacturer
     * @return ManufacturerInterface
     * @throws LocalizedException
     */
    public function save(ManufacturerInterface $manufacturer);

    /**
     * Retrieve block.
     *
     * @param string $manufacturerId
     * @return ManufacturerInterface
     * @throws LocalizedException
     */
    public function getById($manufacturerId);

    /**
     * Retrieve block.
     *
     * @param string $ids
     * @return ManufacturerInterface[]
     * @throws LocalizedException
     */
    public function getByIds($ids);

    /**
     * Retrieve blocks matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return ManufacturerSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete block.
     *
     * @param ManufacturerInterface $manufacturer
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(ManufacturerInterface $manufacturer);

    /**
     * Delete block by ID.
     *
     * @param string $manufacturerId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($manufacturerId);

    /**
     * All manufacturer records
     *
     * @return ManufacturerInterface[]
     */
    public function getAll();
}
