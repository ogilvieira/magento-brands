<?php
class Attain_Brands_Model_Attribute extends Varien_Object {

	public function getOptionArray() {
	
		$attribute = Mage::getSingleton('eav/config')
						->getAttribute('catalog_product', 'brand')
						->getSource()
						->getAllOptions();

		return $attribute;
	}
}