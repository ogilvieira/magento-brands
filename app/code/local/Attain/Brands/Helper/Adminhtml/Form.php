<?php
class Attain_Brands_Helper_Adminhtml_Form extends Mage_Core_Helper_Abstract
{
	public function getWysiwygConfig ($module_name = '/base/') {
		$config = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
		
		return $config->setData( $this->recursiveReplace( $module_name, '/'.(string) Mage::app()->getConfig()->getNode('admin/routers/adminhtml/args/frontName').'/', $config->getData() ) );
	}
	
	public function recursiveReplace($search, $replace, $subject) {
        if ( ! is_array($subject) ) {
            return $subject;
		}
		
        foreach ( $subject as $key => $value ) {
            if ( is_string($value) ) {
                $subject[$key] = str_replace($search, $replace, $value);
			} elseif ( is_array($value) ) {
                $subject[$key] = $this->recursiveReplace($search, $replace, $value);
			}
		}
		
        return $subject;
    }
}