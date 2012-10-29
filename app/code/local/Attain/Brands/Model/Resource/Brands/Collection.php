<?php

class Attain_Brands_Model_Resource_Brands_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
	public function _construct() {
		parent::_construct();
		$this->_init('brands/brands');
	}
	
	public function addStatusToFilter() {
		$this->getSelect()->where('`status` = 1' );
		return $this;
		
	}
	
	public function loadByBrandId( int $id ) {
		$this->getSelect()->where('`attribute_id` = ?', $id );
		return $this;
	}
	
}