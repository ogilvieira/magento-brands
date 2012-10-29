<?php
class Attain_Brands_Block_Adminhtml_Brands extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct() {
		$this->_controller = 'adminhtml_brands';
		$this->_blockGroup = 'brands';
		$this->_headerText = Mage::helper('brands')->__('Item Manager');
		$this->_addButtonLabel = Mage::helper('brands')->__('Add Item');
		parent::__construct();
	}
}