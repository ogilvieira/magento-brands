<?php

class Attain_Brands_Block_Adminhtml_Brands_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct() {
		
		parent::__construct();
		
		$this->_objectId 	= 'id';
		$this->_blockGroup 	= 'brands';
		$this->_controller 	= 'adminhtml_brands';
		
		$this->_updateButton('save', 'label', Mage::helper('brands')->__('Save Item'));
		$this->_updateButton('delete', 'label', Mage::helper('brands')->__('Delete Item'));
		
		$this->_addButton('saveandcontinue', array(
			'label'	 	=> Mage::helper('adminhtml')->__('Save And Continue Edit'),
			'onclick'	=> 'saveAndContinueEdit()',
			'class'	 	=> 'save',
		), -100);

		$this->_formScripts[] = "
			function saveAndContinueEdit(){
				editForm.submit($('edit_form').action+'back/edit/');
			}
		";
	}

	public function getHeaderText() {
		if( Mage::registry('brands_data') && Mage::registry('brands_data')->getId() ) {
			return Mage::helper('brands')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('brands_data')->getTitle()));
		} else {
			return Mage::helper('brands')->__('Add Item');
		}
	}
}