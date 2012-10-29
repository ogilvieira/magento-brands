<?php

class Attain_Brands_Block_Adminhtml_Brands_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm() {
		

		$form = new Varien_Data_Form(
			array(
				'id' 		=> 'edit_form',
				'action' 	=> $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
				'method' 	=> 'post',
				'enctype' 	=> 'multipart/form-data'
			)
		);
		$form->setUseContainer(true);

		$this->setForm($form);
		$fieldset = $form->addFieldset('brands_form', array('legend'=>Mage::helper('brands')->__('Brand information')));
	 
		$fieldset->addField('name', 'text', array(
			'label'	 	=> Mage::helper('brands')->__('Name'),
			'required'	=> true,
			'name'		=> 'name',
		));
		
		$config = Mage::helper('brands/adminhtml_form')->getWysiwygConfig('/brands/');
		
		$fieldset->addField('description', 'editor', array(
			'name'		=> 'description',
			'label'	 	=> Mage::helper('brands')->__('Description'),
			'title'	 	=> Mage::helper('brands')->__('Description'),
			'style'	 	=> 'width:700px; height:250px;',
			'config' 	=> $config,
			'wysiwyg'	=> true,
			'required'	=> true,
			
		));
		
		$fieldset->addField('status', 'select', array(
			'label'	 	=> Mage::helper('brands')->__('Status'),
			'name'		=> 'status',
			'values'	=> array(
				array(
					'value'	 => 1,
					'label'	 => Mage::helper('brands')->__('Enabled'),
				),

				array(
					'value'	 => 2,
					'label'	 => Mage::helper('brands')->__('Disabled'),
				),
			),
		));
		
		$fieldset->addField('attribute_id', 'select', array(
			'label'	 	=> Mage::helper('brands')->__('Brand Name'),
			'name'		=> 'attribute_id',
			'values'	=> Mage::helper('brands')->getAttributes() ,
		));
		
		$fieldset->addField('file_name', 'image', array(
			'label'	 	=> Mage::helper('brands')->__('Image'),
			'required'  => false,
			'name'		=> 'file_name',
		));
	 
		if ( Mage::getSingleton('adminhtml/session')->getBrandsData() ) {
			$form->setValues(Mage::getSingleton('adminhtml/session')->getBrandsData());
			Mage::getSingleton('adminhtml/session')->setBrandsData(null);
		} elseif ( Mage::registry('brands_data') ) {
			$form->setValues(Mage::registry('brands_data')->getData());
		}
		
		return parent::_prepareForm();
	}
}