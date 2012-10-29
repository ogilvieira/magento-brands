<?php

class Attain_Brands_Block_Adminhtml_Brands_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm() {
		
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('brands_form', array('legend'=>Mage::helper('brands')->__('Item information')));
	 
		$fieldset->addField('title', 'text', array(
			'label'	 	=> Mage::helper('brands')->__('Title'),
			'required'	=> true,
			'name'		=> 'title',
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
		
		$fieldset->addField('article', 'editor', array(
			'name'		=> 'article',
			'label'	 	=> Mage::helper('brands')->__('Article'),
			'title'	 	=> Mage::helper('brands')->__('Article'),
			'style'	 	=> 'width:700px; height:500px;',
			'wysiwyg'	=> false,
			'required'	=> true,
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