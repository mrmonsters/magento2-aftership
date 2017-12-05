<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 04/12/2017
 * Time: 2:38 PM
 */

namespace Mrmonsters\Aftership\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface {

	/**
	 * @inheritDoc
	 */
	public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
	{
		$installer = $setup;

		$installer->startSetup();

		/**
		 * Create table 'as_track'
		 */
		$table = $installer->getConnection()
		                   ->newTable($installer->getTable('as_track'))
		                   ->addColumn('track_id', \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT, null, [
			                   'identity' => true,
			                   'nullable' => false,
			                   'primary'  => true,
		                   ], 'Track ID')
		                   ->addColumn('tracking_number', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, [
			                   'nullable' => false,
		                   ])
		                   ->addColumn('ship_comp_code', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, [
			                   'nullable' => false,
		                   ])
		                   ->addColumn('email', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, [
			                   'nullable' => false,
		                   ])
		                   ->addColumn('telephone', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, [
			                   'nullable' => false,
		                   ])
		                   ->addColumn('title', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, [
			                   'nullable' => false,
			                   'default'  => 0,
		                   ])
		                   ->addColumn('posted', \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT, null, [
			                   'nullable' => false,
		                   ])
		                   ->addColumn('order_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, [
			                   'nullable' => false,
		                   ])
		                   ->addIndex($installer->getIdxName('as_track', ['tracking_number']), ['tracking_number'])
		                   ->addIndex($installer->getIdxName('as_track', ['posted']), ['posted'])
		                   ->addIndex($installer->getIdxName('as_track', ['order_id']), ['order_id']);
		$installer->getConnection()->createTable($table);

		$installer->endSetup();
	}

}