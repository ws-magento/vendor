<?php

namespace WS\Manufacturer\Controller\Adminhtml\All;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;
use WS\Manufacturer\Api\ManufacturerRepositoryInterface;
use WS\Manufacturer\Model\ManufacturerFactory;
use WS\Manufacturer\Model\ManufacturerRepository;

/**
 * Items controller
 */
abstract class Items extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    protected $uploaderFactory;
    protected $adapterFactory;
    protected $filesystem;

    /**
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     * @var Filesystem\Driver\File
     */
    protected $_file;

    /**
     * @var ManufacturerFactory
     */
    protected $manFactory;

    /**
     * @var ManufacturerRepository
     */
    protected $manRepository;

    /**
     * Initialize Group Controller
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param DirectoryList $directoryList
     * @param UploaderFactory $uploaderFactory
     * @param AdapterFactory $adapterFactory
     * @param Filesystem $filesystem
     * @param Filesystem\Driver\File $file
     * @param ManufacturerRepositoryInterface $manRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        DirectoryList $directoryList,
        UploaderFactory $uploaderFactory,
        AdapterFactory $adapterFactory,
        Filesystem $filesystem,
        \Magento\Framework\Filesystem\Driver\File $file,
        ManufacturerFactory $manFactory,
        ManufacturerRepositoryInterface $manRepository
    ) {
//        var_dump(23); die;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->directoryList = $directoryList;
        $this->uploaderFactory = $uploaderFactory;
        $this->adapterFactory = $adapterFactory;
        $this->filesystem = $filesystem;
        $this->_file = $file;
        $this->manFactory = $manFactory;
        $this->manRepository = $manRepository;
    }

    /**
     * Initiate action
     *
     * @return this
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Ws_Manufacturer::man_all');
        return $this;
    }

    /**
     * Determine if authorized to perform group actions.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ws_Manufacturer::man');
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function initPage($resultPage)
    {
//        $resultPage->setActiveMenu('WS_Manufacturer::man')
//            ->addBreadcrumb(__('CMS'), __('CMS'))
//            ->addBreadcrumb(__('Static Blocks'), __('Static Blocks'));
        return $resultPage;
    }
}
