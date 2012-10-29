<?php

class Attain_Brands_Adminhtml_BrandsController extends Mage_Adminhtml_Controller_action
{
	protected function _initAction() {
		$this
			->loadLayout()
			->_setActiveMenu('brands/items');
		
		return $this;
	}	
	
	public function indexAction() {
		$this
			->_initAction()
			->renderLayout();
	}
	
	public function editAction() {
		$id	= $this->getRequest()->getParam('id');
		
		$model = Mage::getModel('brands/brands');
		
		if ( $id ) { $model->load($id); }
		
		if ( $model->getId() || $id == 0 ) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			
			if ( ! empty( $data ) ) {
				$model->setData($data);
			}
			
			Mage::register('brands_data', $model);
			
			$this->loadLayout();

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this
				->_setActiveMenu('brands/items')
				->_addContent($this->getLayout()->createBlock('brands/adminhtml_brands_edit'))
				//->_addLeft($this->getLayout()->createBlock('brands/adminhtml_brands_edit_tabs'))
				->renderLayout();
			
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('brands')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
	
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			$model = Mage::getModel('brands/brands');		

			try {
	
				if(isset($_FILES['file_name']['name']) && $_FILES['file_name']['name'] != '') {
					
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS . 'brands' .DS;
					
					//this way the name is saved in DB
					$data['file_name'] =  DS . 'brands' .DS . $_FILES['file_name']['name'];
					
					/* Starting upload */	
					$image = WideImage::load('file_name');
					$image = $image->resize(1600, 1200);

					$image->resize(130, 70, 'inside')->saveToFile($path . $_FILES['file_name']['name']);
					
				} else {
					if(isset($data['fileinputname']['delete']) && $data['fileinputname']['delete'] == 1)
						$data['file_name'] = '';
					else
						$data['file_name'] = $data['file_name']['value'];
				}
				
				$model
					->setData($data)
					->setId($this->getRequest()->getParam('id'));
				
				$model->save();
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('brands')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);
				
				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				
				$this->_redirect('*/*/');
				return;
				
			} catch (Exception $e) {
				
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
				
			}
		}
		
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('brands')->__('Unable to find item to save'));
		
		$this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if ( $this->getRequest()->getParam('id') > 0 ) {
			
			$id = $this->getRequest()->getParam('id');
			
			try {
				Mage::getModel('brands/brands')
					->setId( $id )
					->delete();
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
				
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		
		$this->_redirect('*/*/');
	}

	public function massDeleteAction() {
		$ids = $this->getRequest()->getParam('brands');
		
		if ( ! is_array( $ids ) ) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
		} else {
			try {
				
				foreach ($ids as $id) {
					Mage::getModel('brands/brands')
						->setId($id)
						->delete();
				}
				
				Mage::getSingleton('adminhtml/session')->addSuccess( Mage::helper('adminhtml')->__( 'Total of %d record(s) were successfully deleted', count( $ids ) ) );
				
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		
		$this->_redirect('*/*/index');
	}
	
	public function massStatusAction() {
		$ids 	= $this->getRequest()->getParam('brands');
		$status = $this->getRequest()->getParam('status');
		
		if ( ! is_array( $ids ) ) {
			Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
		} else {
			try {
				foreach ($ids as $id ) {
					Mage::getSingleton('brands/brands')
						->load( $id )
						->setStatus( $status )
						->setIsMassupdate(true)
						->save();
				}
				
				$this->_getSession()->addSuccess( $this->__('Total of %d record(s) were successfully updated', count( $ids ) ) );
				
			} catch (Exception $e) {
				$this->_getSession()->addError($e->getMessage());
			}
		}
		
		$this->_redirect('*/*/index');
	}
	
	public function exportCsvAction() {
		$filename = 'brands.csv';
		$content = 
			$this->getLayout()
				->createBlock('brands/adminhtml_brands_grid')
					->getCsv();
		
		$this->_sendUploadResponse($filename, $content);
	}

	public function exportXmlAction() {
		$filename = 'brands.xml';
		$content =
			$this->getLayout()
				->createBlock('brands/adminhtml_brands_grid')
					->getXml();
		
		$this->_sendUploadResponse($filename, $content);
	}

	protected function _sendUploadResponse($filename, $content, $contentType='application/octet-stream') {
		$response = $this->getResponse();
		$response->setHeader('HTTP/1.1 200 OK','');
		$response->setHeader('Pragma', 'public', true);
		$response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
		$response->setHeader('Content-Disposition', 'attachment; filename='.$filename);
		$response->setHeader('Last-Modified', date('r'));
		$response->setHeader('Accept-Ranges', 'bytes');
		$response->setHeader('Content-Length', strlen($content));
		$response->setHeader('Content-type', $contentType);
		$response->setBody($content);
		$response->sendResponse();
		die;
	}
}