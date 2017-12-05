<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 04/12/2017
 * Time: 4:10 PM
 */

namespace Mrmonsters\Aftership\Model;

use Magento\Framework\Model\AbstractModel;
use Mrmonsters\Aftership\Api\Data\TrackInterface;

class Track extends AbstractModel implements TrackInterface {

	protected $_eventPrefix = 'mrmonsters_aftership_track';

	protected function _construct()
	{
		$this->_init('Mrmonsters\Aftership\Model\ResourceModel\Track');
	}

	/**
	 * @inheritDoc
	 */
	public function getTrackId()
	{
		return $this->getData(self::TRACK_ID);
	}

	/**
	 * @inheritDoc
	 */
	public function getTrackingNumber()
	{
		return $this->getData(self::TRACKING_NUMBER);
	}

	/**
	 * @inheritDoc
	 */
	public function getShipCompCode()
	{
		return $this->getData(self::SHIP_COMP_CODE);
	}

	/**
	 * @inheritDoc
	 */
	public function getEmail()
	{
		return $this->getData(self::EMAIL);
	}

	/**
	 * @inheritDoc
	 */
	public function getTelephone()
	{
		return $this->getData(self::TELEPHONE);
	}

	/**
	 * @inheritDoc
	 */
	public function getTitle()
	{
		return $this->getData(self::TITLE);
	}

	/**
	 * @inheritDoc
	 */
	public function getPosted()
	{
		return $this->getData(self::POSTED);
	}

	/**
	 * @inheritDoc
	 */
	public function getOrderId()
	{
		return $this->getData(self::ORDER_ID);
	}

	/**
	 * @inheritDoc
	 */
	public function setTrackId($trackId)
	{
		return $this->setData(self::TRACK_ID, $trackId);
	}

	/**
	 * @inheritDoc
	 */
	public function setTrackingNumber($trackNo)
	{
		return $this->setData(self::TRACKING_NUMBER, $trackNo);
	}

	/**
	 * @inheritDoc
	 */
	public function setShipCompCode($code)
	{
		return $this->setData(self::SHIP_COMP_CODE, $code);
	}

	/**
	 * @inheritDoc
	 */
	public function setEmail($email)
	{
		return $this->setData(self::EMAIL, $email);
	}

	/**
	 * @inheritDoc
	 */
	public function setTelephone($telephone)
	{
		return $this->setData(self::TELEPHONE, $telephone);
	}

	/**
	 * @inheritDoc
	 */
	public function setTitle($title)
	{
		return $this->setData(self::TITLE, $title);
	}

	/**
	 * @inheritDoc
	 */
	public function setPosted($posted)
	{
		return $this->setData(self::POSTED, $posted);
	}

	/**
	 * @inheritDoc
	 */
	public function setOrderId($orderId)
	{
		return $this->setData(self::ORDER_ID, $orderId);
	}

}