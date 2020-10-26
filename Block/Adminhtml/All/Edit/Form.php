<?php

namespace WS\Manufacturer\Block\Adminhtml\All\Edit;

use Magento\Backend\Block\Widget\Form\Generic;

class Form extends Generic
{
    protected $wysiwygConfig;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    ) {
        $this->wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('manufacturer_all_form');
        $this->setTitle(__('Manufacturer Information'));
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_man');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl('manufacturer/all/save'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                ],
            ]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Manufacturer Information')]);

        if ($model->getId()) {
            $fieldset->addField('manufacturer_id', 'hidden', ['name' => 'manufacturer_id']);
        }
        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Manufacturer Name'), 'title' => __('Manufacturer Name'), 'required' => true]
        );
        $fieldset->addField(
            'image',
            'image',
            ['name' => 'image', 'label' => __('Manufacturer Logo'), 'title' => __('Manufacturer Logo'), 'required' => true]
        );
        $fieldset->addField(
            'description',
            'editor',
            [
                'name' => 'description',
                'label' => __('Manufacturer description'),
                'title' => __('Manufacturer Description'),
                'required' => false,
                'config' => $this->wysiwygConfig->getConfig(),
                'wysiwyg' => true
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
