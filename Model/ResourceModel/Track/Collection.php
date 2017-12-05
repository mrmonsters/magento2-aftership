<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 04/12/2017
 * Time: 4:11 PM
 */

namespace Mrmonsters\Aftership\Model\ResourceModel\Track;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\VersionControl\Collection {

	protected function _construct()
	{
		$this->_init('Mrmonsters\Aftership\Model\Track', 'Mrmonsters\Aftership\Model\ResourceModel\Track');
	}

}