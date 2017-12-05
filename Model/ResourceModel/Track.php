<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 04/12/2017
 * Time: 4:10 PM
 */

namespace Mrmonsters\Aftership\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class Track extends AbstractDb {

	/**
	 * @inheritDoc
	 */
	protected function _construct()
	{
		$this->_init('as_track', 'track_id');
	}

}