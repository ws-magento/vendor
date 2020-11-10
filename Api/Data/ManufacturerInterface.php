<?php

namespace WS\Manufacturer\Api\Data;

/**
 * Interface ManufacturerInterface
 * @package WS\Manufacturer\Api\Data
 *
 *
 */
interface ManufacturerInterface
{
    const ID = 'id';
    const NAME = 'name';
    const DESC = 'description';
    const IMG = 'image';
    const DATE_ADD = 'date_added';


    /**
     * @return integer
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getImage();

    /**
     * @return string
     */
    public function getDateAdded();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param $id
     * @return ManufacturerInterface
     */
    public function setId($id);

    /**
     * @param $name
     * @return ManufacturerInterface
     */
    public function setName($name);

    /**
     * @param $image
     * @return ManufacturerInterface
     */
    public function setImage($image);

    /**
     * @param $description
     * @return ManufacturerInterface
     */
    public function setDescription($description);

    /**
     * @param $date
     * @return mixed
     */
    public function setDateAdded($date);
}
