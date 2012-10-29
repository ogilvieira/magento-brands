<?php

class Attain_Brands_Block_Adminhtml_Brands_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct() {
		parent::__construct();
		$this->setId('brandsGrid');
		$this->setDefaultSort('brands_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}
	
	protected function _prepareCollection() {
		$this->setCollection( Mage::getModel('brands/brands')->getCollection() );
		return parent::_prepareCollection();
	}
	
	protected function _prepareColumns() {
		
		$this->addColumn('brands_id', array(
			'header'	=> Mage::helper('brands')->__('ID'),
			'align'	 	=>'right',
			'width'	 	=> '50px',
			'index'	 	=> 'brands_id',
		));
		
		$this->addColumn('name', array(
			'header'	=> Mage::helper('brands')->__('Name'),
			'align'	 	=>'left',
			'index'	 	=> 'name',
		));
		
		$this->addColumn('attribute_id', array(
			'header'	 	=> Mage::helper('brands')->__('Brand Name'),
			'index'			=> 'attribute_id',
			'type'			=> 'options',
			'options'		=> Mage::helper('brands')->getAttributesGrid() ,
		));
		
		$this->addColumn('status', array(
			'header'	=> Mage::helper('brands')->__('Status'),
			'align'	 	=> 'left',
			'width'	 	=> '80px',
			'index'	 	=> 'status',
			'type'		=> 'options',
			'options'	=> array(
				1 => 'Enabled',
				2 => 'Disabled',
			),
		));
		
		
		$this->addColumn('action',
			array(
				'header'	=>	Mage::helper('brands')->__('Action'),
				'width'	 	=> '100',
				'type'		=> 'action',
				'getter'	=> 'getId',
				'actions'	=> array(
					array(
						'caption'	=> Mage::helper('brands')->__('Edit'),
						'url'		=> array('base'=> '*/*/edit'),
						'field'		=> 'id'
					)
				),
				'filter'	=> false,
				'sortable'	=> false,
				'index'	 	=> 'stores',
				'is_system' => true,
		));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('brands')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('brands')->__('XML'));
		
		return parent::_prepareColumns();
	}
	
	protected function _prepareMassaction() {
		$this->setMassactionIdField('brands_id');
		$this->getMassactionBlock()->setFormFieldName('brands');

		$this->getMassactionBlock()->addItem('delete', array(
			'label'		=> Mage::helper('brands')->__('Delete'),
			'url'		=> $this->getUrl('*/*/massDelete'),
			'confirm'	=> Mage::helper('brands')->__('Are you sure?')
		));
		
		$statuses = Mage::getSingleton('brands/status')->getOptionArray();
		
		array_unshift($statuses, array('label'=>'', 'value'=>''));
		
		$this->getMassactionBlock()->addItem('status', array(
			'label'=> Mage::helper('brands')->__('Change status'),
			'url'	=> $this->getUrl('*/*/massStatus', array('_current'=>true)),
			'additional' => array(
				'visibility' => array(
					'name' => 'status',
					'type' => 'select',
					'class' => 'required-entry',
					'label' => Mage::helper('brands')->__('Status'),
					'values' => $statuses
				)
			)
		));
		return $this;
	}
	
	public function getRowUrl($row) {
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}