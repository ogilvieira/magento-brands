<?php
class Attain_Brands_Helper_Data extends Mage_Core_Helper_Abstract {

	protected $options = array();

	public function getAttributes () {
		return Mage::getModel('brands/attribute')->getOptionArray();
	}
	
	public function getAttributesGrid () {
		
		$options = $this->getAttributes();
		
		foreach( $options as $option ){
			$this->options[$option['value']] = $option['label'];
		}
		return $this->options;
	}
	
}