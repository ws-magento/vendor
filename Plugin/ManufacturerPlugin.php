<?php

namespace WS\Manufacturer\Plugin;

use Magento\Framework\App\Area;
use WS\Manufacturer\Model\Manufacturer;

class ManufacturerPlugin
{
    /**
     * @var bool
     */
    private $isWebApi;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesDriver;

    /**
     * @var string
     */
    private $savePath;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * ManufacturerPlugin constructor.
     * @param \Magento\Framework\App\State $state
     * @param \Magento\Framework\Filesystem $filesDriver
     * @param \Psr\Log\LoggerInterface $logger
     * @param string $savePath
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function __construct(
        \Magento\Framework\App\State $state,
        \Magento\Framework\Filesystem $filesDriver,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Psr\Log\LoggerInterface $logger,
        $savePath
    ) {
        $this->isWebApi = $state->getAreaCode() == Area::AREA_WEBAPI_REST;
        $this->filesDriver = $filesDriver;
        $this->savePath = $savePath;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * @param Manufacturer $model
     * @param $name
     */
    public function beforeSetImage(Manufacturer $model, $value)
    {
        if ($this->isWebApi) {
            if (strpos($value, 'data:image') === 0) {
                /** is base64 string */
                list($type, $data) = explode(';', $value);
                list($null, $ext) = explode('/', $type);
                list($null, $content) = explode(",", $data);

                return $this->saveImage(base64_decode($content), $ext);
            }
        }
        return $value;
    }

    /**
     * @param string|false $content. File contents
     * @param string $ext. File extension
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function saveImage($content, $ext)
    {
        $baseAbsPath = $this->filesDriver
            ->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)
            ->getAbsolutePath();

        /** Generate image name and if exist file with the same name - generate new name */
        do {
            $newImageName = rand(1000, 50000) . ".$ext";
            $temp = implode('/', [
                trim($this->savePath, '/'),
                $newImageName
            ]);
            $newImagePath = implode('/', [
                rtrim($baseAbsPath, '/'),
                $temp
            ]);
        } while (@file_exists($newImagePath));

        $relPart = rtrim($this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA), '/');
        $relPart = parse_url($relPart, PHP_URL_PATH);
        $relPath = implode("/", [
            $relPart,
            $this->savePath,
            $newImageName
        ]);

        if (@file_put_contents($newImagePath, $content)) {
            return $relPath;
        }
        return null;
    }

}
